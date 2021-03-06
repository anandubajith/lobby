<?php
include "../load.php";
require L_DIR . "/includes/src/Update.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <?php \Lobby::head("Update");?>
  </head>
  <body>
    <?php
    \Lobby::doHook("admin.body.begin");
    include "$docRoot/admin/sidebar.php";
    ?>
    <div class="workspace">
      <div class="content">
        <h1>Update</h1>
        <a class='button' href='check-updates.php'>Check For New Releases</a>
        <?php
        $AppUpdates = json_decode(getOption("app_updates"), true);
        if(\H::input("action", "POST") == "updateApps" && H::csrf()){
          foreach($AppUpdates as $appID => $neverMindThisVariable){
            if(isset($_POST[$appID])){
              echo '<iframe src="'. L_URL . "/admin/download.php?type=app&id={$appID}". H::csrf("g") .'" style="border: 0;width: 100%;height: 200px;"></iframe>';
              unset($AppUpdates[$appID]);              
            }
          }
          saveOption("app_updates", json_encode($AppUpdates));
          $AppUpdates = json_decode(getOption("app_updates"), true);
        }
        if(!isset($_GET['step']) && isset($AppUpdates) && count($AppUpdates) != 0){
        ?>
          <h2>Apps</h2>
          <p>App updates are available. Choose which apps to update from the following :</p>
          <form method="POST" clear>
            <?php H::csrf(1);?>
            <table>
              <thead>
                <tr>
                  <td style='width: 5%;'>Update ?</td>
                  <td style='width: 10%;'>App</td>
                  <td style='width: 5%;'>Current Version</td>
                  <td style='width: 20%;'>Latest Version</td>
                </tr>
              </thead>
              <?php              
              echo "<tbody>";
              foreach($AppUpdates as $appID => $latest_version){
                $App = new \Lobby\Apps($appID);
                $AppInfo = $App->info;
                echo '<tr>';
                  echo '<td><input style="vertical-align:top;display:inline-block;" checked="checked" type="checkbox" name="'. $appID .'" /></td>';
                  echo '<td><span style="vertical-align:middle;display:inline-block;margin-left:5px;">'. $AppInfo['name'] .'</span></td>';
                  echo '<td>'. $AppInfo['version'] .'</td>';
                  echo '<td>'. $latest_version .'</td>';
                echo '</tr>';
              }
              ?>
            </tbody></table>
            <input type="hidden" name="action" value="updateApps" />
            <button class="red" style='padding: 10px 15px;' clear>Update Selected Apps</button>
          </form>
        <?php
        }
        if(getOption("lobby_version") == getOption("lobby_latest_version") && !\H::input("action", "POST") == "updateApps"){
          echo "<h2>Lobby</h2>";
          sss("Latest Version", "You are using the latest version of Lobby. There are no new releases yet.");
        }elseif(!isset($_GET['step']) && !\H::input("action", "POST") == "updateApps"){
        ?>
          <h2>Lobby</h2>
          <p>
            Welcome To The Lobby Update Page. A latest version is available for you to upgrade.
          </p>
          <blockquote>
            Latest Version is <?php echo getOption("lobby_latest_version");?>
            released on <?php echo date( "jS F Y", strtotime(getOption("lobby_latest_version_release")) );?>
          </blockquote>
          <p style="margin-bottom: 10px;">
            Lobby will automatically download the latest version and install. In case something happens, Lobby will not be accessible anymore. So backup your database and Lobby installation before you do anything.
            <div clear></div>
            <a class="button" href="backup-db.php">Export Lobby Database</a>
          </p>
        <?php
          if(is_writable(L_DIR)){
            echo '<div clear style="margin-top: 10px;"></div>';
            echo \Lobby::l("/admin/update.php?step=1" . H::csrf("g"), "Setup Lobby Update", "class='button red'");
          }
        }
        if(isset($_GET['step']) && $_GET['step'] != "" && H::csrf()){
          $step = $_GET['step'];
          if($step == 1){
          ?>
            <p>
              Looks like everything is ok. Hope you backed up Lobby installation & Database.
              <div clear></div>
              You can update now.
            </p>
          <?php
            echo \Lobby::l("/admin/update.php?step=2" . H::csrf("g"), "Start Update", "clear class='button green'");
          }elseif($step == 2){
            $version = getOption("lobby_latest_version");
            echo '<iframe src="'. L_URL . "/admin/download.php?type=lobby&id=$version". H::csrf("g") .'" style="border: 0;width: 100%;height: 200px;"></iframe>';
          }
        }
        ?>
      </div>
    </div>
  </body>
</html>
