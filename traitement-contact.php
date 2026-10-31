<?php

// ==========================================
// CONFIGURATION EDÉNÉLIA
// ==========================================

$destinataire = "edenelia.contact@gmail.com";

// IMPORTANT : remplace cette adresse par une adresse
// existante sur ton domaine chez IONOS.
$expediteur = "contact@ton-domaine.fr";

$nom_entreprise = "Edénélia";

// Limites des fichiers
$nombre_max = 8;
$taille_max = 20 * 1024 * 1024; // 20 Mo par fichier
$taille_totale_max = 18 * 1024 * 1024; // 18 Mo au total

// ==========================================
// FONCTION : AFFICHER UN MESSAGE
// ==========================================

function afficherMessage($titre, $message) {
    echo '<!doctype html>
    <html lang="fr">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>' . htmlspecialchars($titre) . ' | Edénélia</title>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <main style="max-width:650px;margin:100px auto;padding:30px;text-align:center;background:#fff;border-radius:12px;">
    <h1>' . htmlspecialchars($titre) . '</h1>
    <p>' . htmlspecialchars($message) . '</p>
    <a href="contact.html" class="button button-primary">Retour au formulaire</a>
    </main>
    </body>
    </html>';
    exit;
}

// ==========================================
// VERIFICATION DE LA METHODE D'ENVOI
// ==========================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    afficherMessage("Accès refusé", "Veuillez utiliser le formulaire de contact.");
}

// ==========================================
// RECUPERATION DES INFORMATIONS
// ==========================================

$nom = trim($_POST["nom"] ?? "");
$prenom = trim($_POST["prenom"] ?? "");
$email = trim($_POST["email"] ?? "");
$telephone = trim($_POST["telephone"] ?? "");
$message = trim($_POST["message"] ?? "");
$consentement = $_POST["consentement"] ?? "";

// ==========================================
// VERIFICATION DES CHAMPS
// ==========================================

if (
    $nom === "" ||
    $prenom === "" ||
    $email === "" ||
    $telephone === "" ||
    $message === "" ||
    $consentement !== "oui"
) {
    afficherMessage(
        "Formulaire incomplet",
        "Merci de remplir tous les champs obligatoires."
    );
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    afficherMessage(
        "Adresse e-mail incorrecte",
        "Merci de vérifier votre adresse e-mail."
    );
}

if (
    preg_match('/[\r\n]/', $email) ||
    preg_match('/[\r\n]/', $nom) ||
    preg_match('/[\r\n]/', $prenom)
) {
    afficherMessage(
        "Informations incorrectes",
        "Merci de vérifier les informations saisies."
    );
}

// ==========================================
// PREPARATION DES PIECES JOINTES
// ==========================================

$pieces_jointes = [];
$taille_totale = 0;

$extensions_autorisees = [
    "jpg"  => ["image/jpeg"],
    "jpeg" => ["image/jpeg"],
    "png"  => ["image/png"],
    "webp" => ["image/webp"],
    "heic" => ["image/heic", "image/heif"],
    "mp4"  => ["video/mp4"],
    "mov"  => ["video/quicktime"]
];

if (isset($_FILES["fichiers"])) {

    $fichiers = $_FILES["fichiers"];

    if (!is_array($fichiers["name"])) {
        afficherMessage("Erreur", "Format de fichiers incorrect.");
    }

    $nombre_fichiers = count($fichiers["name"]);

    if ($nombre_fichiers > $nombre_max) {
        afficherMessage(
            "Trop de fichiers",
            "Vous pouvez joindre 8 fichiers maximum."
        );
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);

    for ($i = 0; $i < $nombre_fichiers; $i++) {

        if ($fichiers["error"][$i] === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        if ($fichiers["error"][$i] !== UPLOAD_ERR_OK) {
            afficherMessage(
                "Erreur de transfert",
                "Un fichier n'a pas pu être transféré. Vérifiez sa taille."
            );
        }

        $chemin = $fichiers["tmp_name"][$i];
        $nom_fichier = basename($fichiers["name"][$i]);
        $taille = $fichiers["size"][$i];

        if (!is_uploaded_file($chemin)) {
            afficherMessage("Erreur", "Fichier non valide.");
        }

        if ($taille > $taille_max) {
            afficherMessage(
                "Fichier trop volumineux",
                "Chaque fichier doit faire 20 Mo maximum."
            );
        }

        $taille_totale += $taille;

        if ($taille_totale > $taille_totale_max) {
            afficherMessage(
                "Fichiers trop volumineux",
                "La taille totale des pièces jointes ne doit pas dépasser 18 Mo."
            );
        }

        $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));
        $type_mime = $finfo->file($chemin);

        if (
            !isset($extensions_autorisees[$extension]) ||
            !in_array($type_mime, $extensions_autorisees[$extension], true)
        ) {
            afficherMessage(
                "Format non accepté",
                "Un fichier possède un format non autorisé."
            );
        }

        $contenu = file_get_contents($chemin);

        if ($contenu === false) {
            afficherMessage("Erreur", "Impossible de lire un fichier.");
        }

        $pieces_jointes[] = [
            "nom" => $nom_fichier,
            "contenu" => $contenu,
            "type" => $type_mime
        ];
    }
}

// ==========================================
// CREATION DU MAIL
// ==========================================

$sujet = "Nouvelle demande de devis - Edénélia";

$boundary = "=_Edenelia_" . bin2hex(random_bytes(16));

$corps_message =
"Nouvelle demande de contact reçue depuis le site Edénélia.

NOM : $nom

PRENOM : $prenom

EMAIL : $email

TELEPHONE : $telephone

MESSAGE :
$message

NOMBRE DE FICHIERS JOINTS : " . count($pieces_jointes) . "

Cordialement,
Site internet Edénélia";

// ==========================================
// CONSTRUCTION DU MESSAGE MULTIPART
// ==========================================

$corps = "--$boundary\r\n";

$corps .= "Content-Type: text/plain; charset=UTF-8\r\n";
$corps .= "Content-Transfer-Encoding: 8bit\r\n\r\n";

$corps .= $corps_message . "\r\n";

// Ajout des photos et vidéos

foreach ($pieces_jointes as $fichier) {

    $nom_securise = preg_replace(
        '/[^A-Za-z0-9._-]/',
        '_',
        $fichier["nom"]
    );

    $corps .= "--$boundary\r\n";

    $corps .= "Content-Type: " . $fichier["type"] .
        "; name=\"" . $nom_securise . "\"\r\n";

    $corps .= "Content-Transfer-Encoding: base64\r\n";

    $corps .= "Content-Disposition: attachment; filename=\"" .
        $nom_securise . "\"\r\n\r\n";

    $corps .= chunk_split(base64_encode($fichier["contenu"])) . "\r\n";
}

$corps .= "--$boundary--";

// ==========================================
// EN-TETES DU MAIL
// ==========================================

$headers = [
    "From: Edénélia <" . $expediteur . ">",
    "Reply-To: " . $email,
    "MIME-Version: 1.0",
    "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"",
    "X-Mailer: PHP/" . phpversion()
];

// ==========================================
// ENVOI DU MAIL
// ==========================================

$resultat = mail(
    $destinataire,
    "=?UTF-8?B?" . base64_encode($sujet) . "?=",
    $corps,
    implode("\r\n", $headers)
);

// ==========================================
// CONFIRMATION
// ==========================================

if ($resultat) {

    afficherMessage(
        "Merci pour votre demande !",
        "Votre demande de devis a bien été transmise à Edénélia. Nous reviendrons vers vous dans les meilleurs délais."
    );

} else {

    afficherMessage(
        "Envoi impossible",
        "Votre demande n'a pas pu être envoyée. Merci de réessayer ultérieurement ou de nous contacter directement."
    );

}

?>