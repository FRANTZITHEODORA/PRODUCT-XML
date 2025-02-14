<?php
class Products
{
    private $xml_file_path = '';

    public function __construct($xml_file_path = '')
    {
        $this->xml_file_path = $xml_file_path;
    }

    public function print_html_table_with_all_products()
    {
        if (empty($this->xml_file_path) || !file_exists($this->xml_file_path)) {
            die("Το αρχείο XML δεν υπάρχει.");
        }

        $xmldata = simplexml_load_file($this->xml_file_path) or die("Αποτυχία φόρτωσης του XML αρχείου.");
        echo "<table class='table table-striped table-bordered'>";
        echo "<tr><th>Όνομα</th><th>Τιμή</th><th>Ποσότητα</th><th>Κατηγορία</th><th>Κατασκευαστής</th><th>Barcode</th><th>Βάρος</th><th>Διαθέσιμο</th><th>Διαθεσιμότητα</th></tr>";

        if (isset($xmldata->PRODUCTS) && isset($xmldata->PRODUCTS->PRODUCT)) {
            foreach ($xmldata->PRODUCTS->PRODUCT as $prod) {
                $this->print_html_of_one_product_line($prod);
            }
        }

        echo "</table>";
    }

    private function print_html_of_one_product_line($prod)
    {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($prod->NAME) . "</td>";
        echo "<td>" . htmlspecialchars($prod->PRICE) . "</td>";
        echo "<td>" . htmlspecialchars($prod->QUANTITY) . "</td>";
        echo "<td>" . htmlspecialchars($prod->CATEGORY) . "</td>";
        echo "<td>" . htmlspecialchars($prod->MANUFACTURER) . "</td>";
        echo "<td>" . htmlspecialchars($prod->BARCODE) . "</td>";
        echo "<td>" . htmlspecialchars($prod->WEIGHT) . "</td>";
        echo "<td>" . htmlspecialchars($prod->INSTOCK) . "</td>";
        echo "<td>" . htmlspecialchars($prod->AVAILABILITY) . "</td>";
        echo "</tr>";
    }

    public function add_product_to_xml($product_data)
    {
        if (empty($this->xml_file_path) || !file_exists($this->xml_file_path)) {
            return "Το αρχείο XML δεν υπάρχει.";
        }

        $xml = simplexml_load_file($this->xml_file_path) or die("Αποτυχία φόρτωσης του XML αρχείου.");

        // Έλεγχος ότι το name είναι συμπληρωμένο
        if (empty($product_data['name'])) {
            return "Το πεδίο 'name' είναι υποχρεωτικό.";
        }

        // Έλεγχος για αρνητικές τιμές στα πεδία price και quantity
        foreach (['price', 'quantity'] as $key) {
            if (isset($product_data[$key]) && $product_data[$key] !== '' && (!is_numeric($product_data[$key]) || $product_data[$key] < 0)) {
                return "Το πεδίο '$key' πρέπει να είναι ένας μη-αρνητικός αριθμός.";
            }
        }

        // Έλεγχος για ύπαρξη του προϊόντος με βάση το όνομα ή το barcode
        foreach ($xml->PRODUCTS->PRODUCT as $existing_product) {
            if ((string) $existing_product->BARCODE === $product_data['barcode'] || 
                (string) $existing_product->NAME === $product_data['name']) {
                return "Το προϊόν υπάρχει ήδη στη λίστα!";
            }
        }

        // Δημιουργία νέου κόμβου PRODUCT και escaping για αποφυγή XSS
        $newProduct = $xml->PRODUCTS->addChild('PRODUCT');
        $newProduct->addChild('NAME', htmlspecialchars($product_data['name']));
        $newProduct->addChild('PRICE', htmlspecialchars($product_data['price']));
        $newProduct->addChild('QUANTITY', htmlspecialchars($product_data['quantity']));
        $newProduct->addChild('CATEGORY', htmlspecialchars($product_data['category']));
        $newProduct->addChild('MANUFACTURER', htmlspecialchars($product_data['manufacturer']));
        $newProduct->addChild('BARCODE', htmlspecialchars($product_data['barcode']));
        $newProduct->addChild('WEIGHT', htmlspecialchars($product_data['weight']));
        $newProduct->addChild('INSTOCK', htmlspecialchars($product_data['instock']));
        $newProduct->addChild('AVAILABILITY', htmlspecialchars($product_data['availability']));

        // Αποθήκευση στο XML αρχείο
        $xml->asXML($this->xml_file_path);
        return ""; // Επιτυχής καταχώρηση
    }

    public function delete_products_starting_with_test()
    {
        if (empty($this->xml_file_path) || !file_exists($this->xml_file_path)) {
            return "Το αρχείο XML δεν υπάρχει.";
        }

        // Φόρτωση του XML αρχείου με DOMDocument
        $dom = new DOMDocument;
        $dom->load($this->xml_file_path);
        $xpath = new DOMXPath($dom);

        // Εύρεση προϊόντων που ξεκινούν με "test" και διαγραφή τους
        $products = $xpath->query("//PRODUCT[starts-with(NAME, 'test') or starts-with(NAME, 'TEST')]");
        $deletedCount = 0;

        foreach ($products as $product) {
            $product->parentNode->removeChild($product);
            $deletedCount++;
        }

        // Αποθήκευση μόνο αν έγινε διαγραφή
        if ($deletedCount > 0) {
            $dom->save($this->xml_file_path);
            return "Τα προϊόντα που ξεκινούν με 'test' διαγράφηκαν με επιτυχία.";
        } else {
            return "";
        }
    }
}
?>
