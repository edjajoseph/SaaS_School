<nav class="pcoded-navbar">
  <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
  <div class="pcoded-inner-navbar main-menu">

      <div class="pcoded-navigation-label">Navigation</div>
      <ul class="pcoded-item pcoded-left-item">
          <li class="@if ($activePage == 'home') active @endif">
              <a href="{{ route('enseignant') }}">
                  <span class="pcoded-micon"><i class="ti-home"></i><b>N</b></span>
                  <span class="pcoded-mtext">Tableau de bord</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          
          <!--<li class="pcoded-hasmenu">
              <a href="javascript:void(0)">
                  <span class="pcoded-micon"><i class="ti-layout"></i><b>P</b></span>
                  <span class="pcoded-mtext">Page layouts</span>
                  <span class="pcoded-badge label label-warning">NEW</span>
                  <span class="pcoded-mcaret"></span>
              </a>
              <ul class="pcoded-submenu">

                  <li class=" pcoded-hasmenu">
                      <a href="javascript:void(0)">
                          <span class="pcoded-micon"><i class="icon-pie-chart"></i></span>
                          <span class="pcoded-mtext">Vertical</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                      <ul class="pcoded-submenu">
                          <li class=" ">
                              <a href="menu-static.html">
                                  <span class="pcoded-micon"><i class="icon-chart"></i></span>
                                  <span class="pcoded-mtext">Static Layout</span>
                                  <span class="pcoded-mcaret"></span>
                              </a>
                          </li>
                          <li class=" ">
                              <a href="menu-header-fixed.html">
                                  <span class="pcoded-micon"><i class="icon-chart"></i></span>
                                  <span class="pcoded-mtext">Header Fixed</span>
                                  <span class="pcoded-mcaret"></span>
                              </a>
                          </li>
                          <li class=" ">
                              <a href="menu-compact.html">
                                  <span class="pcoded-micon"><i class="icon-chart"></i></span>
                                  <span class="pcoded-mtext">Compact</span>
                                  <span class="pcoded-mcaret"></span>
                              </a>
                          </li>
                          <li class=" ">
                              <a href="menu-sidebar.html">
                                  <span class="pcoded-micon"><i class="icon-chart"></i></span>
                                  <span class="pcoded-mtext">Sidebar Fixed</span>
                                  <span class="pcoded-mcaret"></span>
                              </a>
                          </li>

                      </ul>
                  </li>
                  <li class=" pcoded-hasmenu">
                      <a href="javascript:void(0)">
                          <span class="pcoded-micon"><i class="icon-pie-chart"></i></span>
                          <span class="pcoded-mtext">Horizontal</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                      <ul class="pcoded-submenu">
                          <li class=" ">
                              <a href="menu-horizontal-static.html">
                                  <span class="pcoded-micon"><i class="icon-chart"></i></span>
                                  <span class="pcoded-mtext">Static Layout</span>
                                  <span class="pcoded-mcaret"></span>
                              </a>
                          </li>
                          <li class=" ">
                              <a href="menu-horizontal-fixed.html">
                                  <span class="pcoded-micon"><i class="icon-chart"></i></span>
                                  <span class="pcoded-mtext">Fixed layout</span>
                                  <span class="pcoded-mcaret"></span>
                              </a>
                          </li>
                          <li class=" ">
                              <a href="menu-horizontal-icon.html">
                                  <span class="pcoded-micon"><i class="icon-chart"></i></span>
                                  <span class="pcoded-mtext">Static With Icon</span>
                                  <span class="pcoded-mcaret"></span>
                              </a>
                          </li>
                          <li class=" ">
                              <a href="menu-horizontal-icon-fixed.html">
                                  <span class="pcoded-micon"><i class="icon-chart"></i></span>
                                  <span class="pcoded-mtext">Fixed With Icon</span>
                                  <span class="pcoded-mcaret"></span>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <li class=" ">
                      <a href="menu-bottom.html">
                          <span class="pcoded-micon"><i class="icon-pie-chart"></i></span>
                          <span class="pcoded-mtext">Bottom Menu</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="box-layout.html">
                          <span class="pcoded-micon"><i class="icon-pie-chart"></i></span>
                          <span class="pcoded-mtext">Box Layout</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="menu-rtl.html">
                          <span class="pcoded-micon"><i class="icon-pie-chart"></i></span>
                          <span class="pcoded-mtext">RTL</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>

              </ul>
          </li>
          <li class="">
              <a href="navbar-light.html">
                  <span class="pcoded-micon"><i class="ti-layout-cta-right"></i><b>N</b></span>
                  <span class="pcoded-mtext">Navigation</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="pcoded-hasmenu">
              <a href="javascript:void(0)">
                  <span class="pcoded-micon"><i class="ti-view-grid"></i><b>W</b></span>
                  <span class="pcoded-mtext">Widget</span>
                  <span class="pcoded-badge label label-danger">100+</span>
                  <span class="pcoded-mcaret"></span>
              </a>
              <ul class="pcoded-submenu">
                  <li class="">
                      <a href="widget-statistic.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Statistic</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="widget-data.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Data</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="widget-chart.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Chart Widget</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
              </ul>
          </li>
      </ul>

      <div class="pcoded-navigation-label">Gestion des utilisateurs</div>
      <ul class="pcoded-item pcoded-left-item">          
            <li class="@if ($activePage == 'cptpersonnel') active @endif">
                <a href="{{ route('users.personnel',['act'=>'perso']) }}">
                    <span class="pcoded-micon"><i class="fa fa-user"></i></span>
                    <span class="pcoded-mtext">Comptes du personnel</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="@if ($activePage == 'cpteleve') active @endif">
                <a href="{{ route('users.eleve',['act'=>'elev']) }}">
                    <span class="pcoded-micon"><i class="fa fa-user-plus"></i><b>F</b></span>
                    <span class="pcoded-mtext">Compte des élèves</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
                 
      </ul>

      <div class="pcoded-navigation-label">Gestion des paramètres</div>
      <ul class="pcoded-item pcoded-left-item">
          <li class="pcoded-hasmenu @if ($activeModule == 'scolaire') active pcoded-trigger @endif">
              <a href="javascript:void(0)">
                  <span class="pcoded-micon"><i class="ti-timer"></i><b>BC</b></span>
                  <span class="pcoded-mtext">Périodiques</span>
                  <span class="pcoded-mcaret"></span>
              </a>
              <ul class="pcoded-submenu">
                 <li class="@if ($activePage == 'type_decoupage') active @endif">
                      <a href="{{ route('type_decoupage.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Type de découpage</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'rubrique_decoupage') active @endif">
                      <a href="{{ route('rubrique_decoupage.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Rubriques de découpage</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'annee_scolaire') active @endif">
                      <a href="{{ route('annee_scolaire.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Années scolaires</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'decoupage') active @endif">
                      <a href="{{ route('decoupage.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Découpage </span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'jour') active @endif">
                      <a href="{{ route('jour.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Jours </span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  
              </ul>
          </li>
          <li class="pcoded-hasmenu @if ($activeModule == 'structure') active pcoded-trigger @endif">
              <a href="javascript:void(0)">
                  <span class="pcoded-micon"><i class="fa fa-globe"></i><b>AC</b></span>
                  <span class="pcoded-mtext">Géographique</span>
                  <span class="pcoded-mcaret"></span>
              </a>
              <ul class="pcoded-submenu">
                  <li class="@if ($activePage == 'pays') active @endif">
                      <a href="{{ route('pays.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Pays</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'commune') active @endif">
                      <a href="{{ route('commune.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Commune</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'ville') active @endif">
                      <a href="{{ route('ville.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Ville</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                                    

              </ul>
          </li>
          <li class="pcoded-hasmenu @if ($activeModule == 'enseignement') active pcoded-trigger @endif">
              <a href="javascript:void(0)">
                  <span class="pcoded-micon"><i class="fa fa-leanpub"></i><b>EC</b></span>
                  <span class="pcoded-mtext">Académiques</span>
                  <span class="pcoded-mcaret"></span>
              </a>
              <ul class="pcoded-submenu">
                  <li class="@if ($activePage == 'cycle_enseignement') active @endif">
                      <a href="{{ route('cycle_enseignement.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Cycle d'enseignement</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'filiere') active @endif">
                      <a href="{{ route('filiere.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Filière</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'groupe_matiere') active @endif">
                      <a href="{{ route('groupe_matiere.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Groupe matière</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'matiere') active @endif">
                      <a href="{{ route('matiere.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Matière</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'niveau') active @endif">
                      <a href="{{ route('niveau.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Niveau</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'serie') active @endif">
                      <a href="{{ route('serie.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Série</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>                  
                  <li class="@if ($activePage == 'type_evaluation') active @endif">
                      <a href="{{ route('type_evaluation.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Type d'évaluation</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'type_examen') active @endif">
                      <a href="{{ route('type_examen.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Type d'examen</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>

              </ul>
          </li>
          <li class="pcoded-hasmenu @if ($activeModule == 'finance') active pcoded-trigger @endif">
              <a href="javascript:void(0)">
                  <span class="pcoded-micon"><i class="fa fa-line-chart"></i><b>AC</b></span>
                  <span class="pcoded-mtext">Financier</span>
                  <span class="pcoded-mcaret"></span>
              </a>
              <ul class="pcoded-submenu">
                 <li class="@if ($activePage == 'frais') active @endif">
                    <a href="{{ route('frais.index') }}">
                        <span class="pcoded-micon"><i class="fa fa-cc-visa"></i><b>LP</b></span>
                        <span class="pcoded-mtext">Frais</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                  </li>          
                  <li class="@if ($activePage == 'taux') active @endif">
                      <a href="{{ route('taux.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Taux horaire</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class="@if ($activePage == 'scolarite') active @endif">
                      <a href="{{ route('scolarite.index') }}">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Scolarité</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>            

              </ul>
          </li>
          <li class="pcoded-hasmenu @if ($activeModule == 'administratif') active pcoded-trigger @endif">
              <a href="javascript:void(0)">
                  <span class="pcoded-micon"><i class="fa fa-building"></i><b>AC</b></span>
                  <span class="pcoded-mtext">Administratif</span>
                  <span class="pcoded-mcaret"></span>
              </a>
              <ul class="pcoded-submenu">
                <li class="@if ($activePage == 'emploi') active @endif">
                    <a href="{{ route('emploi.index') }}">
                        <span class="pcoded-micon"><i class="fa fa-briefcase"></i><b>FP</b></span>
                        <span class="pcoded-mtext">Emplois</span>                        
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="@if ($activePage == 'fonction') active @endif">
                    <a href="{{ route('fonction.index') }}">
                        <span class="pcoded-micon"><i class="ti-shortcode"></i><b>FS</b></span>
                        <span class="pcoded-mtext">Fonctions</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="@if ($activePage == 'corpsmetier') active @endif">
                    <a href="{{ route('corpsmetier.index') }}">
                        <span class="pcoded-micon"><i class="ti-shortcode"></i><b>FS</b></span>
                        <span class="pcoded-mtext">Corps de métier</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
              </ul>
          </li>
          <!--<li class=" ">
              <a href="animation.html">
                  <span class="pcoded-micon"><i class="ti-reload rotate-refresh"></i><b>A</b></span>
                  <span class="pcoded-mtext">Animations</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class=" ">
              <a href="sticky.html">
                  <span class="pcoded-micon"><i class="ti-layers-alt"></i><b>S</b></span>
                  <span class="pcoded-mtext">Sticky Notes</span>
                  <span class="pcoded-badge label label-danger">HOT</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="pcoded-hasmenu">
              <a href="javascript:void(0)">
                  <span class="pcoded-micon"><i class="ti-star"></i><b>I</b></span>
                  <span class="pcoded-mtext">Icons</span>
                  <span class="pcoded-mcaret"></span>
              </a>
              <ul class="pcoded-submenu">
                  <li class=" ">
                      <a href="icon-font-awesome.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Font Awesome</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="icon-themify.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Themify</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="icon-simple-line.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Simple Line Icon</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="icon-ion.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Ion Icon</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="icon-material-design.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Material Design</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="icon-icofonts.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Ico Fonts</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="icon-weather.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Weather Icon</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="icon-typicons.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Typicons</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
                  <li class=" ">
                      <a href="icon-flags.html">
                          <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                          <span class="pcoded-mtext">Flags</span>
                          <span class="pcoded-mcaret"></span>
                      </a>
                  </li>
              </ul>
          </li>
      </ul>
      <div class="pcoded-navigation-label">Gestion administrative</div>
      <ul class="pcoded-item pcoded-left-item">          
            <li class="@if ($activePage == 'etablissement') active @endif">
                <a href="{{ route('etablissement.index') }}">
                    <span class="pcoded-micon"><i class="fa fa-bank"></i></span>
                    <span class="pcoded-mtext">Etablissement</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="@if ($activePage == 'classe') active @endif">
                <a href="{{ route('classe.index') }}">
                    <span class="pcoded-micon"><i class="fa fa-sitemap"></i><b>F</b></span>
                    <span class="pcoded-mtext">Classe</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>   
            <li class="@if ($activePage == 'personnel') active @endif">
                <a href="{{ route('personnel.index') }}">
                    <span class="pcoded-micon"><i class="fa fa-briefcase"></i><b>FM</b></span>
                    <span class="pcoded-mtext">Personnel</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
                 
      </ul>
      <div class="pcoded-navigation-label">Gestion de la scolarité</div>
      <ul class="pcoded-item pcoded-left-item">                    
          
          <li class="@if ($activePage == 'eleve') active @endif">
              <a href="{{ route('eleve.index') }}">
                  <span class="pcoded-micon"><i class="fa fa-female"></i><b>F</b></span>
                  <span class="pcoded-mtext">Eleves</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="@if ($activePage == 'inscription') active @endif">
              <a href="{{ route('inscription.index') }}">
                  <span class="pcoded-micon"><i class="fa fa-file-excel-o"></i><b>E</b></span>
                  <span class="pcoded-mtext">Inscriptions</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
      </ul>
      <div class="pcoded-navigation-label">Gestion financière</div>
      <ul class="pcoded-item pcoded-left-item">
          <li class="@if ($activePage == 'validation') active @endif">
              <a href="{{ route('inscription.valide') }}">
                  <span class="pcoded-micon"><i class="fa fa-line-chart"></i><b>LP</b></span>
                  <span class="pcoded-mtext">Droit d'inscription</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="@if ($activePage == 'versement') active @endif">
              <a href="{{ route('versement.index') }}">
                  <span class="pcoded-micon"><i class="fa fa-cc-visa"></i></i><b>LP</b></span>
                  <span class="pcoded-mtext">Autres versements</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="@if ($activePage == 'echeancier') active @endif">
              <a href="{{ route('echeancier.index') }}">
                  <span class="pcoded-micon"><i class="fa fa-pie-chart"></i><b>LP</b></span>
                  <span class="pcoded-mtext">Frais de scolarité</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
      </ul>-->
      <div class="pcoded-navigation-label">gestion des planifications</div>
      <!--<ul class="pcoded-item pcoded-left-item">
            <li class="@if ($activePage == 'emploi_temps') active @endif">
              <a href="{{ route('emploi_temps.index') }}">
                  <span class="pcoded-micon"><i class="fa fa-calendar"></i><b>F</b></span>
                  <span class="pcoded-mtext">Emplois du temps</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>-->
         <li class="@if ($activePage == 'planification') active @endif">
              <a href="{{ route('planification.index') }}">
                  <span class="pcoded-micon"><i class="fa fa-calendar-o"></i><b>LP</b></span>
                  <span class="pcoded-mtext">programme évaluation</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <div class="pcoded-navigation-label">gestion des cours</div>
      <ul class="pcoded-item pcoded-left-item">
            
         <li class="@if ($activePage == 'cours') active @endif">
              <a href="{{ route('cours.index') }}">
                  <span class="pcoded-micon"><i class="ti-blackboard"></i><b>LP</b></span>
                  <span class="pcoded-mtext">Cours</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="@if ($activePage == 'support') active @endif">
              <a href="#">
                  <span class="pcoded-micon"><i class="fa fa-file"></i><b>LP</b></span>
                  <span class="pcoded-mtext">Support</span>
                  <span class="pcoded-badge label label-warning">NEW</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <!--<li class="@if ($activePage == 'pointage') active @endif">
              <a href="{{ route('pointage.index') }}">
                  <span class="pcoded-micon"><i class="fa fa-calendar-o"></i><b>LP</b></span>
                  <span class="pcoded-mtext">Pointages</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>-->
          
      </ul>
      <div class="pcoded-navigation-label">Gestion des évaluations</div>
      <ul class="pcoded-item pcoded-left-item">
          <li class="@if ($activePage == 'evaluation') active @endif">
              <a href="{{ route('evaluation.index') }}">
                  <span class="pcoded-micon"><i class="fa fa-sort-numeric-asc"></i><b>LP</b></span>
                  <span class="pcoded-mtext">Evaluation</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="@if ($activePage == 'depot') active @endif">
              <a href="#">
                  <span class="pcoded-micon"><i class="fa fa-file-o"></i><b>LP</b></span>
                  <span class="pcoded-mtext">Dépôt</span>
                  <span class="pcoded-badge label label-warning">NEW</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <!--<li class="@if ($activePage == 'examen') active @endif">
              <a href="{{ route('examen.index') }}">
                  <span class="pcoded-micon"><i class="fa fa-graduation-cap"></i><b>LP</b></span>
                  <span class="pcoded-mtext">Délibératiion (Examen)</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
      </ul>      
      <div class="pcoded-navigation-label">Repporting</div>
      <ul class="pcoded-item pcoded-left-item">
          
          <li class="@if ($activePage == 'recherche') active @endif"> 
              <a href="{{ route('recherche.listing') }}">
                  <span class="pcoded-micon"><i class="fa fa-file-text-o"></i><b>D</b></span>
                  <span class="pcoded-mtext">Liste de classe</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="@if ($activePage == 'recherche_ind') active @endif">
              <a href="{{ route('recherche.rech_eleve') }}">
                  <span class="pcoded-micon"><i class="fa fa-file-archive-o"></i><b>S</b></span>
                  <span class="pcoded-mtext">Recherche individuelle</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="">
              <a href="sample-page.html">
                  <span class="pcoded-micon"><i class="ti-layout-sidebar-left"></i><b>S</b></span>
                  <span class="pcoded-mtext">Fiche inscription</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="">
              <a href="sample-page.html">
                  <span class="pcoded-micon"><i class="ti-layout-sidebar-left"></i><b>S</b></span>
                  <span class="pcoded-mtext">Livret scolaire</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="">
              <a href="sample-page.html">
                  <span class="pcoded-micon"><i class="ti-layout-sidebar-left"></i><b>S</b></span>
                  <span class="pcoded-mtext">Fiche de versement</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
          <li class="">
              <a href="sample-page.html">
                  <span class="pcoded-micon"><i class="fa fa-calculator"></i><b>S</b></span>
                  <span class="pcoded-mtext">Bulletin</span>
                  <span class="pcoded-mcaret"></span>
              </a>
          </li>
      </ul>
      <div class="pcoded-navigation-label">Communication</div>
      <ul class="pcoded-item pcoded-left-item">          
            <li class="@if ($activePage == 'mail') active @endif">
                <a href="{{ route('etablissement.index') }}">
                    <span class="pcoded-micon"><i class="fa fa-at"></i></span>
                    <span class="pcoded-mtext">MailingList</span>
                    <span class="pcoded-badge label label-warning">NEW</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="@if ($activePage == 'sms') active @endif">
                <a href="{{ route('classe.index') }}">
                    <span class="pcoded-micon"><i class="fa fa-envelope"></i><b>F</b></span>
                    <span class="pcoded-mtext">SMSListe</span>
                    <span class="pcoded-badge label label-warning">NEW</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
                 
      </ul>-->
  </div>
</nav>