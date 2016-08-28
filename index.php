<?php
require_once 'config.php';

use Erpk\Harvester\Module\Country\CountryModule;
use Erpk\Harvester\Module\Politics\PoliticsModule;
use Erpk\Common\EntityManager;
use Erpk\Common\Entity\Country;

$pm = new PoliticsModule($client);
$cm = new CountryModule($client);

$em = EntityManager::getInstance();
$countries = $em->getRepository(Country::class);
$country = $countries->findOneByCode('US');
$society = $cm->getSociety($country);
?>
<!DOCTYPE html>
<html>
<head>
    <title>R&R Party Tracking</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link href="css/cover.css" type="text/css" rel="stylesheet" />
   <!-- <link href="css/style.css" />-->

    <script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!--<script src="js/script.js"></script>-->
</head>
<body>
<div class="site-wrapper">
    <div class="site-wrapper-inner">
        <div class="cover-container">
            <div class="masthead clearfix">
                <div class="inner">
                    <h3 class="masthead-brand">The Federalist Party</h3>
                </div>
            </div>
            <div class="inner cover">
                <h1 class="cover-heading"><u><b>Record Party Counts</b></u></h1>
                <?php
                if (isset($_GET['date'])) {
                    ?>
                    <h3 class="cover-heading"><?php echo date("F j, Y", strtotime($_GET['date'])) ?></h3>
                    <table class="table table-hover">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM count WHERE date = ?");
                    $stmt->execute([$_GET['date']]);
                    while ($row = $stmt->fetch()) {
                        if ($row['party'] != 1) {
                            $date = date("Y-m-d", mktime(0, 0, 0, date("m", strtotime($_GET['date'])), date("j", strtotime($_GET['date'])) - 1, date("Y", strtotime($_GET['date']))));
                            $party = $pm->getParty($row['party']);
                            ?>
                            <tr>
                                <th><?php echo $party['name'] ?></th>
                                <td>
                                    <?php
                                    echo $row['total'] . "&nbsp;";
                                    $check = $pdo->prepare("SELECT * FROM count WHERE party = ? AND date = ?");
                                    if ($check->execute([$row['party'], $date])) {
                                        $last = $check->fetch();
                                        if ($last['total'] > $row['total']) {
                                            $color = 'red';
                                            $pre = '-';
                                        } else if ($last['total'] < $row['total']) {
                                            $color = 'green';
                                            $pre = '+';
                                        } else {
                                            $color = 'gray';
                                            $pre = '';
                                        }
                                        $diff = abs($last['total'] - $row['total']);
                                        ?>
                                        <span style="color: <?php echo $color ?>">(<?php echo $pre . $diff ?>)</span>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        } else {

                        }
                    }
                    ?>
                    </table>
                    <?php
                } else {
                    $stmt = $pdo->query("SELECT * FROM count GROUP BY date ORDER BY date DESC");
                    while ($row = $stmt->fetch()) {
                        ?>
                        <h3 class="cover-heading"><a href="index.php?date=<?php echo $row['date'] ?>"><?php echo date("F j, Y", strtotime($row['date'])) ?></a></h3>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="mastfoot">
                <div class="inner">
                    <p>&copy; Copyright Federalist Party 2016</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
