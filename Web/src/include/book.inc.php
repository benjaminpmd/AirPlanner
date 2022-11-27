<?php
include "./include/functions.inc.php";
include_once "./classes/User.class.php";

$aircrafts = get_aircrafts();

$message_flights = null;
$message_book = null;
$flights = null;
$fis = null;

if($_GET["type"] && ($_GET["type"] == "book-flight")) {
    if($_GET["date"]) {
        if ($_GET["is-lesson"]) {
            $fis = get_available_fi($_GET["date"], $_GET["start-time"], $_GET["end-time"]);
            if ($_GET["fi-id"]) {
                $message_book = book_flight($user);
            }
        }
        else {
            $message_book = book_flight($user);
        }
    }
    else {
        $message_flights = "Veuillez spécifier une date";
    }
}

if($_GET["type"] && $_GET["date"]) {
    $flights = get_flights_per_date($_GET["date"], $_GET["registration"]);
}
?>

<section class="mx-8 mb-10 md:mx-auto md:max-w-3xl p-5 rounded-xl items-center object-center bg-gray-200 dark:bg-gray-700">
    <h2 class="text-center text-2xl">Voir les créneaux</h2>
    <?php if($message_flights) echo "<p class=\"text-center p-3\">$message_flights</p>\n"; ?>
    <form action="/book.php" method="GET" class="text-center">
        <input type="hidden" name="type" value="check-flights">
        <label>Date</label>
        <input type="date" required name="date" class="input-value" value="<?php echo $_GET["date"] ?>" />
        <label>Appareil</label>
        <select name="registration" class="input-value">
            <?php
            foreach ($aircrafts as $key => $value) {
                if ($value["registration"] == $_GET["registration"]) {
                    echo '<option value="'.$value["registration"].'" selected>'.$value["registration"].'</option>';
                }
                else echo '<option value="'.$value["registration"].'">'.$value["registration"].'</option>';
            }
            ?>
        </select>
        <input type="submit" value="voir les créneaux" class="input-submit" />
    </form>
    <article>
        <h3 class="text-center text-xl">Créneaux de vol</h3>

        <table class="w-full rounded-xl text-center border-2 border-slate-400 dark:border-slate-500">
            <thead class="w-full border-b-2 border-slate-400 dark:border-slate-500"><tr><th>Début</th><th>Fin</th><th>Leçon</th></tr></thead>
            <tbody>
            <?php
                if ($flights) {
                    foreach($flights as $key => $flight) {
                        echo "<tr class=\"w-full bg-sky-300 dark:bg-sky-700\"><td>".$flight["start_time"]."</td><td>".$flight["end_time"]."</td><td>"."Non"."</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </article>
</section>

<section class="mx-8 mb-10 md:mx-auto md:max-w-3xl p-5 rounded-xl items-center object-center bg-gray-200 dark:bg-gray-700">
    <h2 class="text-center text-2xl">Réserver</h2>
    <?php if($message_book) echo "<p class=\"text-center p-3\">$message_book</p>\n"; ?>
    <form action="/book.php" method="GET" class="text-center grid grid-cols-1 md:grid-cols-4">
        <input type="hidden" name="type" value="book-flight">
        <input type="hidden" name="registration" value="<?php echo $_GET["registration"]; ?>">
        <input type="hidden" name="date" value="<?php echo $_GET["date"]; ?>" />
        <label class="input-label md:col-span-3">Heure de début</label>
        <input type="time" name="start-time" value="<?php echo $_GET["start-time"]; ?>" class="input-value" required />
        <label class="input-label md:col-span-3">Heure de fin</label>
        <input type="time" name="end-time" value="<?php echo $_GET["end-time"]; ?>" class="input-value" required />
        <label class="input-label md:col-span-3">Vol avec instructeur</label>
        <input type="checkbox" name="is-lesson" class="items-center" <?php if($_GET["is-lesson"]) echo "checked"; ?> />
        <?php 
        if($_GET["is-lesson"]) {
            echo '<label class="input-label md:col-span-3">Instructeur</label><select name="fi-id" class="input-value">';
            foreach ($fis as $key => $fi) {
                if ($fi["fi_id"] == $_GET["fi-id"]) {
                    echo "<option value=\"".$fi["fi_id"]."\" selected>".$fi["fi_code"]."</option>";
                }
                else echo "<option value=\"".$fi["fi_id"]."\">".$fi["fi_code"]."</option>";
            }
            echo "</select>\n";
        }
        
        ?>
        <input type="submit" value="Réserver un vol" class="input-submit md:col-span-4" />
    </form>
</section>