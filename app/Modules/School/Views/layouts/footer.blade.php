<footer class="footer">
  <div class=" container-fluid ">
     <nav>
      <ul>
        <li>
          <a href="#" class="nav-link" data-toggle="modal" data-target="#ConditionModal">
            {{__(" CONDITIONS D'UTILISATION")}}
          </a>
        </li>
        <li>
          <a href="#" class="nav-link" data-toggle="modal" data-target="#ConfidentModal">
            {{__(" CONFIDENTIALITE")}}
          </a>
        </li>       
        <li>
          <a href="#" class="nav-link" data-toggle="modal" data-target="#SecurityModal">
            {{__(" SECURITE")}}
          </a>
        </li>
        <!--<li>
          <a href="https://www.updivision.com" target="_blank">
            {{__(" Updivision")}}</a>
        </li>-->
      </ul>
    </nav> 
    <div class="copyright" id="copyright">{{__("Digitalis & Ivoire Global Engineering Service (IGES)")}}
      &copy;
      
      <script>
        document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
      </script>, {{__("Copyright")}}
      <a href="#" target="_blank">{{__("@2022")}}</a>
    </div>
  </div>
</footer>

<!-- Condition d'utillisation-->

<div class="modal fade" id="ConditionModal" tabindex="-1" role="dialog" aria-labelledby="ConditionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
      <div class="modal-header text-center position-relative w-100 pr-5" style="background-color:chocolate">
          <h4 class="title modal-title w-100 mt-2 mb-0" id="ConditionModalLabel">
            {{ __('CONDITION D`\'UTILISATION') }}
          </h4>
          <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close" style="top: 15px; right: 15px; z-index: 10;">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>

      <div class="modal-body">
          <p>Sophi@cademia(Sophia-Academia) est une plateforme de service educative,qui offre au monde educatif et universitaite, une gestion globale et une vue 360 de leurs activités.
          Elle intègre au seins d'une même application, la gestion admnistrative, financière et scolaire. L'accès au système se fait par abonnement annuel ou mensuel à une formule selon le contexte de votre activité.
          NB: Sophia-Academia ext la propriètée exclusive de la sociète IGES & Digitals, ne peut être vendu ou reproduite sans autorisation préalable de ladite société.</p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Confidentialité -->

<div class="modal fade" id="ConfidentModal" tabindex="-1" role="dialog" aria-labelledby="ConfidentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
      <div class="modal-header text-center position-relative w-100 pr-5" style="background-color:chocolate">
          <h4 class="title modal-title w-100 mt-2 mb-0" id="ConfidentModalLabel">
            {{ __('CONFIDENTIALITE') }}
          </h4>
          <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close" style="top: 15px; right: 15px; z-index: 10;">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>

      <div class="modal-body">
          <p>Sophia-Academia, garrantie la confidentialité de vos données en séparant les activités de chaque partenaire au seins d'une structure propre à elle.</p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Confidentialité-->

<div class="modal fade" id="SecurityModal" tabindex="-1" role="dialog" aria-labelledby="SecurityModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
      <div class="modal-header text-center position-relative w-100 pr-5" style="background-color:chocolate">
          <h4 class="title modal-title w-100 mt-2 mb-0" id="SecurityModalLabel">
            {{ __('SECURITE') }}
          </h4>
          <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close" style="top: 15px; right: 15px; z-index: 10;">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>

      <div class="modal-body">
          <p>Sophia-Academia est dévéloppé dans les règle strictes de l'architecture logicielle. Elle est logé dans un environnement hautement sécurisé avec une équipe technique hautement qualifiè.
            Elle garantie, l'intégrité physique de vos donnés et la restauration en 24 heures maximales en cas de panne.</p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>