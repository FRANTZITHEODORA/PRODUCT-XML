# TODO
1. Σκοπός του products.php είναι να εκτυπώνει ένα HTML table με όλα τα προϊόντα που βρίσκονται στο products.xml. 
Συμπλήρωσε τον κώδικα στις δύο συναρτήσεις της κλάσης ώστε να εκτυπώσεις το table.

2. Θα πρέπει να προσθέσουμε τη λειτουργικότητα καταχώρησης μίας νέας εγγραφής product στο xml. 
Για να γίνει αυτό, θα πρέπει να φτιάξουμε μία html form στην οποία ο χρήστης θα μπορεί να δίνει ως input τα στοιχεία ενός product.
Το product θα αποθηκεύεται στο xml. 
Υποχρεωτικό πεδίο για το product είναι το NAME.
Είμαστε ελεύθεροι να εμφανισουμε τη φόρμα είτε σε νέο php είτε στο products.php
Προτείνουμε η συνάρτηση που θα κάνει την νέα εγγραφή στο xml να μπει μέσα στην υπάρχουσα κλάση Products

Λειτουργικότητα που χρησιμοποιήθηκε:
1. Εκτύπωση Προϊόντων από XML Αρχείο
Το products.php είναι σχεδιασμένο να διαβάζει τα δεδομένα από το αρχείο products.xml και να τα εμφανίζει σε ένα HTML table. Ο κώδικας προστέθηκε στην κλάση Products ώστε να εκτυπώνει όλα τα προϊόντα χρησιμοποιώντας τις παρακάτω συναρτήσεις:

print_html_table_with_all_products(): Εκτυπώνει έναν πίνακα HTML με τα στοιχεία των προϊόντων.
print_html_of_one_product_line(): Εκτυπώνει κάθε γραμμή του πίνακα για κάθε προϊόν.

2. Καταχώρηση Νέας Εγγραφής Προϊόντος
Προστέθηκε μια HTML φόρμα στο products.php, όπου ο χρήστης μπορεί να εισάγει τα στοιχεία ενός νέου προϊόντος. Η κλάση Products περιλαμβάνει τη συνάρτηση add_product_to_xml() για να αποθηκεύσει το νέο προϊόν στο XML. Υποχρεωτικό πεδίο της φόρμας είναι το NAME.

Λειτουργίες και Προστασία Κώδικα
1. Απαιτούμενο Υποχρεωτικό Πεδίο: Μόνο το πεδίο name είναι υποχρεωτικό, σύμφωνα με τις οδηγίες. 
2. Δεν μπορεί να προστεθεί προϊόν με την ίδια ονομασία ή το ίδιο Barcode.
3. Αποτροπή XSS: Για προστασία από XSS επιθέσεις, τα δεδομένα εισόδου περνούν από τη συνάρτηση htmlspecialchars, ώστε να εμφανίζονται χωρίς να εκτελούν HTML ή JavaScript κώδικα.
4. Έλεγχος Αρνητικών Τιμών: Τα πεδία price και quantity ελέγχονται ώστε να είναι αριθμητικά και μη-αρνητικά, διασφαλίζοντας ότι δεν εισάγονται αρνητικές τιμές.
5. Διαγραφή Δοκιμαστικών Εγγραφών: Η συνάρτηση delete_products_starting_with_test() διαγράφει προϊόντα των οποίων το name ξεκινά με "test" ή "TEST". Αυτή η λειτουργία προστέθηκε για δοκιμαστικούς σκοπούς και δεν ενεργοποιείται από το frontend.

Οδηγίες για Τελικό Έλεγχο
Έλεγχος Εκτύπωσης Προϊόντων: Στην αρχική φόρτωση της σελίδας, όλα τα προϊόντα του products.xml εμφανίζονται σωστά στον HTML πίνακα.
Δοκιμή Καταχώρησης Προϊόντος: Κατά την καταχώρηση νέου προϊόντος μέσω της φόρμας, ελέγχεται ότι συμπληρώθηκε το υποχρεωτικό πεδίο name και ότι το νέο προϊόν δεν υπάρχει ήδη στην λίστα ελέγχοντας το όνομα και το barcode. 
Διαχείριση Μηνυμάτων: Μετά την καταχώρηση, εμφανίζεται μήνυμα επιτυχίας ή αποτυχίας, ανάλογα με το αποτέλεσμα της καταχώρησης.