<?php

namespace App\Menu\View\render;


/**
 * Construit tous les blocs liés à la navigation
 */
class Nav
{

  /**
   * Renvoi la barre de navigation de l'application
   *
   * @param  mixed $admin email de l'admin
   * @return string
   */
  public static function renderNav(?string $admin): string
  {
    return "<nav class='navbar navbar-expand-lg border-bottom' style='background-color: #e3f2fd;'>
               <div class='container-fluid'>
                 <div class='col-md-2'>
                   <a class='navbar-brand' href='index.php?controller=home'>App Menu</a>
                 </div>
                 <div class='collapse navbar-collapse d-flex' id='navbarSupportedContent'>
                   <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
                     <li class='nav-item'>
                       <a class='nav-link' role='button' href='index.php?controller=Group&action=list'>
                         Menus
                       </a>
                     </li>
                     <li class='nav-item'>
                       <a class='nav-link' role='button' href='#'>
                         News
                       </a>
                     </li>
                   </ul>
                 </div>
                 <div>
                   <div class='dropdown '>
                     <button class='btn dropdown-toggle text-secondary' type='button' id='dropdownMenuButton1' data-bs-toggle='dropdown' aria-expanded='false'>
                       <i class='fa-solid fa-user p-2'></i><span>$admin</span>
                     </button>
                     <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton1'>
                       <li class='p-2'><a class='p-2 text-decoration-none text-secondary' href='index.php?controller=user&action=list'>Users</a></li>
                       <li class='p-2'><a class='p-2 text-decoration-none text-secondary' href='index.php?controller=login&action=logout'>Logout</a></li>
                     </ul>
                   </div>
                 </div>
               </div>
             </nav>";
  }
}
