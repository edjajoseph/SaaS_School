<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\Schedule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoodleCalendarService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.moodle.url', env('MOODLE_URL', 'https://moodle.your-university.edu'));
        $this->token   = config('services.moodle.token', env('MOODLE_TOKEN', 'your-moodle-api-token'));
    }

    /**
     * Crée un événement dans le calendrier Moodle
     */
    public function createEvent(Schedule $schedule): ?int
    {
        try {
            $schedule->loadMissing(['subject', 'room', 'schoolClass']);

            $response = Http::post("{$this->baseUrl}/webservice/rest/server.php", [
                'wstoken' => $this->token,
                'wsfunction' => 'core_calendar_create_calendar_events',
                'moodlewsrestformat' => 'json',
                'events' => [
                    [
                        'name' => ($schedule->subject->custom_name ?? $schedule->subject->name ?? 'Cours') . " ({$schedule->session_type})",
                        'description' => "Classe: " . ($schedule->schoolClass->name ?? 'N/A') . " | Salle: " . ($schedule->room->name ?? 'N/A'),
                        'format' => 1,
                        'eventtype' => 'course',
                        'courseid' => $schedule->subject->moodle_course_id ?? 1,
                        'timestart' => strtotime("next " . $this->getDayName($schedule->day_of_week) . " " . $schedule->start_time),
                        'timeduration' => strtotime($schedule->end_time) - strtotime($schedule->start_time),
                    ]
                ]
            ]);

            if ($response->successful() && isset($response->json()['events'][0]['id'])) {
                return $response->json()['events'][0]['id'];
            }
        } catch (\Exception $e) {
            Log::error("Erreur Moodle API Create: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Supprime un événement du calendrier Moodle
     */
    public function deleteEvent(int $moodleEventId): bool
    {
        try {
            $response = Http::post("{$this->baseUrl}/webservice/rest/server.php", [
                'wstoken' => $this->token,
                'wsfunction' => 'core_calendar_delete_calendar_events',
                'moodlewsrestformat' => 'json',
                'events' => [
                    ['eventid' => $moodleEventId, 'repeat' => 0]
                ]
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Erreur Moodle API Delete: " . $e->getMessage());
            return false;
        }
    }

    private function getDayName(int $dayOfWeek): string
    {
        $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
        return $days[$dayOfWeek] ?? 'Monday';
    }
}