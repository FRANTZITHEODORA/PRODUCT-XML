<?php
session_start(); // Ξεκινάμε τη συνεδρία πριν από οποιαδήποτε έξοδο δεδομένων
require_once("./lib.php");

$productsList = new Products("./products.xml");

$message = ''; // Αρχικοποίηση μηνύματος
$formData = []; // Αρχικοποίηση δεδομένων φόρμας


//Η delete_products_starting_with_test() δημιουργήθηκε και θα καλείται μόνο 
//για δοκιμαστικούς σκοπούς
//$result = $productsList->delete_products_starting_with_test();
//echo $result;

// Έλεγχος για μηνύματα στη συνεδρία και εμφάνιση
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Καθαρισμός μηνύματος μετά την εμφάνιση
}

// Έλεγχος αν έχει υποβληθεί η φόρμα
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Συλλογή δεδομένων από τη φόρμα
    $formData = [
        'name' => $_POST['name'] ?? '',
        'price' => $_POST['price'] ?? '',
        'quantity' => $_POST['quantity'] ?? '',
        'category' => $_POST['category'] ?? '',
        'manufacturer' => $_POST['manufacturer'] ?? '',
        'barcode' => $_POST['barcode'] ?? '',
        'weight' => $_POST['weight'] ?? '',
        'instock' => $_POST['instock'] ?? '',
        'availability' => $_POST['availability'] ?? ''
    ];

    // Κλήση της συνάρτησης για προσθήκη του νέου προϊόντος στο XML
    $result = $productsList->add_product_to_xml($formData);

    // Έλεγχος για το αποτέλεσμα
    if ($result === "") {
        $_SESSION['message'] = "<div class='alert alert-success'>Το προϊόν προστέθηκε με επιτυχία!</div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>$result</div>";
    }
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h1>List of products</h1>

<?php
$productsList->print_html_table_with_all_products();

if (!empty($message)) {
    echo "<div style='margin: 20px; padding: 15px; font-weight: bold; border-radius: 5px;' class='";
    echo strpos($message, 'επιτυχία') !== false ? 'alert alert-success' : 'alert alert-danger';
    echo "'>$message</div>";
}
?>

<div class="container mt-5">
    <h2>Προσθήκη Νέου Προϊόντος</h2>
    <form action="products.php" method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label for="name">Όνομα Προϊόντος (Υποχρεωτικό):</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($formData['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Τιμή:</label>
            <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($formData['price'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="quantity">Ποσότητα:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($formData['quantity'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="category">Κατηγορία:</label>
            <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($formData['category'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="manufacturer">Κατασκευαστής:</label>
            <input type="text" class="form-control" id="manufacturer" name="manufacturer" value="<?php echo htmlspecialchars($formData['manufacturer'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="barcode">Barcode:</label>
            <input type="text" class="form-control" id="barcode" name="barcode" value="<?php echo htmlspecialchars($formData['barcode'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="weight">Βάρος:</label>
            <input type="text" class="form-control" id="weight" name="weight" value="<?php echo htmlspecialchars($formData['weight'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="instock">Διαθέσιμο:</label>
              <select name="instock" id="instock" class="form-control" required>
                <option value="">Επιλέξτε</option>
                <option value="ΝΑΙ">ΝΑΙ</option>
                <option value="ΟΧΙ">ΟΧΙ</option>
            </select>
        </div>
        <div class="form-group">
            <label for="availability">Διαθεσιμότητα:</label>
           <input type="text" class="form-control" id="availability" name="availability" value="<?php echo htmlspecialchars($formData['availability'] ?? ''); ?>">
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Προσθήκη Προϊόντος</button>
    </form>
</div>

</body>
</html>
