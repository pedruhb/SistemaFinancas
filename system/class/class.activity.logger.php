<?php

if (!defined("PHB")) die();
class ActivityLogger
{

  public static function add(string $mensagem, int $userId = -1)
  {

    if ($userId = -1)
      $userId = $_SESSION['id'];

    $selectUser = Database::connection()->prepare("SELECT discord_log_webhook_url, discord_log_webhook_enabled FROM users WHERE id = ?");
    $selectUser->bindParam(1, $userId);
    $selectUser->execute();

    if ($selectUser->rowCount() == 1) {

      $userData = $selectUser->fetch();

      $insertLog = Database::connection()->prepare("INSERT INTO `activity_log` (`user_id`, `log`, `ip`, `timestamp`) VALUES (?, ?, ?, ?);");
      $insertLog->bindParam(1, $userId);
      $insertLog->bindParam(2, $mensagem);
      $insertLog->bindValue(3, getUserIp());
      $insertLog->bindValue(4, time());
      $insertLog->execute();

      if ((bool) $userData["discord_log_webhook_enabled"]) {
        $embed = json_encode([
          "content" => "",
          "embeds" => [
            [
              "color" => hexdec("993399"),
              "footer" => [
                "text" => "Caso não tenha sido você, troque a senha imediatamente!",
              ],
              "author" => [
                "name" => "Sistema Financeiro",
              ],
              "fields" => [
                [
                  "name" => "Ação",
                  "value" => $mensagem,
                  "inline" => false
                ],
                [
                  "name" => "IP",
                  "value" =>  getUserIp(),
                  "inline" => true
                ],
                [
                  "name" => "Data",
                  "value" => date("d/m/y H:i", time()),
                  "inline" => true
                ]
              ]
            ]
          ],
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $userData["discord_log_webhook_url"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $embed);
        $response = json_decode(curl_exec($ch));
        if ($response->message == "Unknown Webhook") {
          $desativarWebhook = Database::connection()->prepare("UPDATE users SET discord_log_webhook_enabled = 'false' WHERE id = ?");
          $desativarWebhook->bindValue(1, $userId);
          $desativarWebhook->execute();
        }
      }
    }
  }
}
