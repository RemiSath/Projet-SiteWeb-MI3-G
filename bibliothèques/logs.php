<?php
function enregistrerLogIncident($type, $message, $email = "", $details = [])
{
    $dirData = __DIR__ . "/../data";
    $fichierLogs = $dirData . "/logs.json";

    if (!is_dir($dirData)) {
        mkdir($dirData, 0777, true);
    }

    $logs = file_exists($fichierLogs)
        ? json_decode(file_get_contents($fichierLogs), true)
        : [];

    if (!is_array($logs)) {
        $logs = [];
    }

    $logs[] = [
        "id" => uniqid("log_", true),
        "type" => $type,
        "message" => $message,
        "email" => strtolower(trim($email)),
        "ip" => $_SERVER["REMOTE_ADDR"] ?? "",
        "user_agent" => substr($_SERVER["HTTP_USER_AGENT"] ?? "", 0, 200),
        "details" => $details,
        "date" => date("Y-m-d H:i:s")
    ];

    file_put_contents(
        $fichierLogs,
        json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );
}
?>