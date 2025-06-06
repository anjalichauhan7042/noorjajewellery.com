<?php
if (isset($_GET['pincode'])) {
    $pincode = $_GET['pincode'];
    $url = "https://api.postalpincode.in/pincode/" . $pincode;

    $response = file_get_contents($url);
    $responseData = json_decode($response, true);

    if ($responseData[0]['Status'] == "Success" && !empty($responseData[0]['PostOffice'])) {
        $city = $responseData[0]['PostOffice'][0]['District'];
        $state = $responseData[0]['PostOffice'][0]['State'];

        echo json_encode([
            "status" => "success",
            "city"   => $city,
            "state"  => $state
        ]);
    } else {
        echo json_encode(["status" => "error"]);
    }
} else {
    echo json_encode(["status" => "error"]);
}
?>
