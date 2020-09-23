<?php
require 'config.php';
require 'models/Auth.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();
$ActiveMenu = "home";

require 'partials/header.php';
require 'partials/menu.php';
?>
<section class="feed mt-10">
...

</section>

<?php
require 'partials/footer.php';
?>