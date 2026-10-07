<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Où rediriger l'utilisateur après l'inscription (non utilisé ici car on utilise redirect()->away())
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * 1. Validation des données du formulaire
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['required', 'string', 'max:255'],
            // Vérifie dans la table globale 'domains' que le sous-domaine n'est pas déjà pris
            'subdomain' => ['required', 'string', 'alpha_dash', 'max:50'],
        ]);
    }

    /**
     * 2. Création du Tenant, du domaine, et de l'utilisateur dans la base client
     */
    protected function create(array $data)
    {
        // Nettoyage du sous-domaine (ex: "Hôtel Marina" devient "hotel-marina")
        $subdomainSlug = Str::slug($data['subdomain']);

        // Créer le Tenant (Déclenche le pipeline : Base SQL -> Migrations -> LaratrustSeeder)
        $tenant = Tenant::create([
            'id' => $subdomainSlug,
            'name' => $data['company_name'],
            'plan' => 'trial',
        ]);

        // Associer le sous-domaine local (ex: hotel-marina.localhost)
        $tenant->domains()->create([
            'domain' => $subdomainSlug,
        ]);

        // Variable pour récupérer l'utilisateur créé dans le scope du Tenant
        $user = null;

        // Entrer temporairement dans la nouvelle base de données du client pour y créer le compte
        $tenant->run(function () use ($data, &$user) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Optionnel : Assigner automatiquement le rôle de 'directeur' à ce créateur via Laratrust
            $user->addRole('directeur'); 
        });

        // Extrait dynamiquement 'saas_hotel.test' ou 'votre-domaine.com'
        $centralDomain = parse_url(config('app.url'), PHP_URL_HOST);

        // Stocker l'URL de redirection dans la session pour que la méthode d'enregistrement puisse la lire
        session(['tenant_redirect_url' => 'http://' . $subdomainSlug . '.' . $centralDomain.'/']);

        return $user;
    }

    /**
     * Surcharger la méthode d'inscription globale pour empêcher la connexion automatique centrale
     */
    public function register(Request $request)
    {
        // 1. Exécuter la validation classique
        $this->validator($request->all())->validate();

        // 2. Déclencher votre méthode create() (qui génère le Tenant, sa base, son admin, et son rôle)
        event(new Registered($user = $this->create($request->all())));

        // 3. Récupérer l'URL du sous-domaine stockée en session
        $redirectUrl = session('tenant_redirect_url', '/login');

        // 4. Nettoyer la session centrale
        session()->forget('tenant_redirect_url');

        // 5. Rediriger directement l'utilisateur vers la page de connexion de SON hôtel
        // Sans essayer de le connecter sur le site vitrine central
        return redirect()->away($redirectUrl . 'login');
    }

    /**
     * Cette méthode peut être laissée vide ou supprimée car la méthode register() du dessus prend désormais le dessus
     */
    protected function registered(Request $request, $user)
    {
        // Ne rien mettre ici
    }
}
