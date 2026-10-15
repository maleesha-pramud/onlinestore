<?php

include './includes/connection.php';

// XML feed of the active properties. Optional filters: ?id=2 (one property), ?validate=1 (check against the DTD)
$propertyId = $_GET['id'] ?? '';
$conditions = ["p.status_id = 1"];

if ($propertyId !== '') {
    if (!Validation::validateId($propertyId)) {
        header('Content-Type: text/plain; charset=UTF-8');
        echo "Invalid property id";
        exit();
    }
    $conditions[] = "p.id = '" . Database::escape($propertyId) . "'";
}

$propertiesStmt = Database::search("
    SELECT p.*, c.name AS category_name, AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count
    FROM properties p
    JOIN categories c ON p.categories_id = c.id
    LEFT JOIN reviews r ON p.id = r.properties_id
    WHERE " . implode(" AND ", $conditions) . "
    GROUP BY p.id
    ORDER BY p.id
");

// Build the XML document
$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('properties');
$root->setAttribute('generated', date('Y-m-d'));
$root->setAttribute('count', (string) $propertiesStmt->num_rows);
$dom->appendChild($root);

function addText($dom, $parent, $name, $text) {
    $element = $dom->createElement($name);
    $element->appendChild($dom->createTextNode($text));
    $parent->appendChild($element);
    return $element;
}

while ($row = $propertiesStmt->fetch_assoc()) {
    $property = $dom->createElement('property');
    $property->setAttribute('id', $row['id']);

    addText($dom, $property, 'title', $row['title']);
    addText($dom, $property, 'category', $row['category_name']);

    // Descriptions are saved as HTML, the feed only needs the plain text
    $plain = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($row['description']), ENT_QUOTES, 'UTF-8')));
    addText($dom, $property, 'description', mb_substr($plain, 0, 300));
    addText($dom, $property, 'address', $row['address']);

    $capacity = $dom->createElement('capacity');
    $capacity->setAttribute('guests', $row['guests']);
    $capacity->setAttribute('bedrooms', $row['bedrooms']);
    $capacity->setAttribute('beds', $row['beds']);
    $capacity->setAttribute('bathrooms', $row['bathrooms']);
    $property->appendChild($capacity);

    addText($dom, $property, 'price', number_format((float) $row['base_price'], 2, '.', ''));

    $rating = addText($dom, $property, 'rating', number_format((float) $row['avg_rating'], 1, '.', ''));
    $rating->setAttribute('reviews', $row['review_count']);

    $amenities = $dom->createElement('amenities');
    $amenitiesStmt = Database::search("
        SELECT a.name FROM amenities a
        JOIN properties_has_amenities pa ON a.id = pa.amenities_id
        WHERE pa.properties_id = '" . $row['id'] . "'
    ");
    while ($amenity = $amenitiesStmt->fetch_assoc()) {
        addText($dom, $amenities, 'amenity', $amenity['name']);
    }
    $property->appendChild($amenities);

    $images = $dom->createElement('images');
    foreach (explode(',', $row['images']) as $image) {
        if ($image !== '') {
            addText($dom, $images, 'image', '/assets/images/properties/' . $image);
        }
    }
    $property->appendChild($images);

    $root->appendChild($property);
}

$body = $dom->saveXML($root);
$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<!DOCTYPE properties SYSTEM \"/xml/properties.dtd\">\n" . $body . "\n";

// ?validate=1 : check the document against xml/properties.dtd and report the result
if (isset($_GET['validate'])) {
    header('Content-Type: text/plain; charset=UTF-8');

    $dtdPath = __DIR__ . '/xml/properties.dtd';
    $checkXml = str_replace('SYSTEM "/xml/properties.dtd"', 'SYSTEM "' . $dtdPath . '"', $xml);

    libxml_use_internal_errors(true);
    $checkDom = new DOMDocument();
    $checkDom->loadXML($checkXml, LIBXML_DTDLOAD);

    if ($checkDom->validate()) {
        echo "XML is valid against properties.dtd (" . $propertiesStmt->num_rows . " properties)";
    } else {
        echo "XML is NOT valid:\n";
        foreach (libxml_get_errors() as $error) {
            echo '- ' . trim($error->message) . "\n";
        }
    }
    exit();
}

header('Content-Type: application/xml; charset=UTF-8');
echo $xml;
