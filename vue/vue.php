<?php

class Vue{

  // Header des pages
  // Nécessite une connexion Internet pour récupérer les symboles FontAwesome et la font Oswald
  // Peut fonctionner sans connexion Internet néanmoins
  function header($dark){
    header("Content-type: text/html; charset=utf-8"); ?>
    <html>
    <head>
      <link rel="icon" href="favicon.ico">
      <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
      <link rel="stylesheet" type="text/css" href="css/fontawesome.css" />
      <link rel="stylesheet" type="text/css" href="css/fontawesome.css" />
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
      <link href="https://fonts.googleapis.com/css?family=Oswald:300" rel="stylesheet">
      <?php
        if($dark) echo '<link rel="stylesheet" type="text/css" href="css/dark.css" />';
        else echo '<link rel="stylesheet" type="text/css" href="css/light.css" />'
      ?>
      <link rel="stylesheet" type="text/css" href="css/default.css" />
    </head>
    <?php
  }

  // Barre de navigation après connexion
  // Prend en paramètre la page actuelle et son lien (GET)
  function navbar($dark, $num, $link){
    ?>
    <body>
      <nav class="navbar navbar-light bg-light navbar-expand-lg">
        <a class="navbar-brand" href="#">
          <?php
            if($dark) echo '<img src="img/ogol.png" height="50" class="d-inline-block align-top" alt="Bridges">';
            else echo '<img src="img/logo.gif" height="50" class="d-inline-block align-top" alt="Bridges">';
          ?>
        </a>
        <ul class="navbar-nav mr-auto">
          <li class="nav-item <?php if($num==1) echo 'active'; ?>">
            <a class="nav-link" href="index.php">Accueil</a>
          </li>
          <li class="nav-item <?php if($num==2) echo 'active'; ?>">
            <a class="nav-link" href="index.php?leaderboard">Classement</a>
          </li>
        </ul>
        <?php
          if($dark) echo '<a href="index.php?dark'.$link.'" class="mr-5 login city"><i class="fas fa-moon"></i></a>';
          else echo '<a href="index.php?dark'.$link.'" class="mr-5 login city"><i class="far fa-moon"></i></a>';
        ?>
        <a href="index.php?logout" class="mr-3 login city">
          <i class="fas fa-power-off"></i>
        </a>
      </nav>
      <br><br>
      <?php
  }

  // Affiche une fenêtre modale pour les informations
  // Prend en paramètre le titre, la description de la modale et le lien de retour du bouton "Fermer" (GET)
  function modal($titre, $desc, $link){
    ?>
    <div class="modal-backdrop fade show"></div>
    <div id="exampleModalCenter" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" style="display: block; padding-right: 17px;">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle"><?php echo $titre ?></h5>
          </div>
          <div class="modal-body">
            <p><?php echo $desc ?></p>
          </div>
          <div class="modal-footer">
            <a href="index.php<?php echo $link ?>" class="btn btn-primary">Fermer</a>
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  // Page d'inscription
  function formInscription(){ ?>
    <body>
      <br/>
      <img src="img/logo.gif" alt="Logo Bridges">
      <br/>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Inscription</h5>
          </div>
          <form method="post" action="index.php">
            <div class="modal-body">

                <input type="text" name="loginInscription" class="form-control" placeholder="Identifiant" aria-label="Username">
                <br>
                <input type="password" name="passwordInscription" class="form-control" placeholder="Mot de passe" aria-label="Username">

            </div>
            <div class="modal-footer">
              <a href="index.php" class="btn btn-primary">Retour</a>
              <input type="submit" class="btn btn-success" value="S'inscrire">
            </div>
          </form>
        </div>
      </div>
      <br/><br/>
    </body>
    <?php
  }

  // Page de connexion
  function demandePseudo(){ ?>
    <body>
      <br/>
      <img src="img/logo.gif" alt="Logo Bridges">
      <br/>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Connexion</h5>
          </div>
          <form method="post" action="index.php">
            <div class="modal-body">

                <input type="text" name="login" class="form-control" placeholder="Identifiant" aria-label="Username">
                <br>
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" aria-label="Username">

            </div>
            <div class="modal-footer">
              <a href="index.php?register" class="btn btn-primary">S'inscrire</a>
              <input type="submit" class="btn btn-primary" value="Se connecter">
            </div>
          </form>
        </div>
      </div>
    </body>
    <?php
  }

  // Page de jeu
  function demandeBridges($villes, $x1, $y1){ ?>
      <form action="index.php" method="post">
        <a href="index.php?undo" class="btn btn-primary" style="height: 38px"><i class='fas fa-undo' style="padding-top: 5px"></i></a>
        <input type="submit" class="btn btn-primary" name="reset" value="Réinitialiser" />
      </form>
      <?php
        for ($i=0; $i < $villes->getSize(); $i++) {
          echo '<div class="d-flex justify-content-center">';
          for ($j=0; $j < $villes->getSize(); $j++) {
            if($villes->existe($i,$j)){
              if($villes->getVille($i,$j) instanceof Ville)
                if($x1 != -1 && $y1 != -1){
            			if($x1==$i && $y1==$j) echo "<a class='city size center selected' href='index.php?x1=".$x1."&y1=".$y1."&x2=".$i."&y2=".$j."'>".$villes->getVille($i,$j)->getNombrePontsMax()."</a>";
            			else echo "<a class='city size center' href='index.php?x1=".$x1."&y1=".$y1."&x2=".$i."&y2=".$j."'>".$villes->getVille($i,$j)->getNombrePontsMax()."</a>";
            		} else echo "<a class='city size center' href='index.php?x1=".$i."&y1=".$j."'>".$villes->getVille($i,$j)->getNombrePontsMax()."</a>";
              else echo "<div class='size ".$villes->getVille($i,$j)."'></div>";
            }
            else echo "<a href='index.php' class='size'></a>";
          }
          echo "</div>";
        }
      ?>
      </div>
    </body>
  <?php
  }

  // Page du classement
  function leaderboard($stats, $scores){ ?>
      <div class='d-flex justify-content-center'>
        <?php if(empty($stats)) echo "<div class='card' style='width: 18rem;'><div class='card-header'>Vous n'avez pas de statistiques !</div></div></div>";
        else { ?>
        <div class="card" style="width: 18rem;">
          <div class="card-header">
            Statistiques de <strong><?php echo $stats[0]["pseudo"]?></strong>
          </div>
          <ul class="list-group list-group-flush justify-content-left">
            <li class="list-group-item">Jouées : <?php echo $stats[0]["games"]?></li>
            <li class="list-group-item">Gagnées : <?php echo $stats[0]["wins"]?></li>
            <li class="list-group-item">Perdues : <?php echo $stats[0]["losses"]?></li>
            <li class="list-group-item">Ratio : <?php echo round($stats[0]["wins"] / $stats[0]["games"] * 100, 2)?>%</li>
          </ul>
        </div>
      </div>
      <?php } ?>
      <br><br>
      <div class='d-flex justify-content-center'>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Pseudo</th>
              <th scope="col">Victoires</th>
              <th scope="col">Ratio</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $i = 1;
              foreach ($scores as $row) {
                $ratio = $row["wins"] / $row["games"] * 100;
                echo "<tr><th scope='row'>".$i."</th><td>".$row["pseudo"]."</td><td>".$row["wins"]."</td><td>".round($ratio, 2)."%</td></tr>";
                $i++;
              }
            ?>
          </tbody>
        </table>
      </div>
    </body>
  <?php
  }
}

?>
