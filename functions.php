<?php
# . Made by Soren Isager @ Sorenisager.com @ https://github.com/sorenisager/
# . Free to use, MIT license
# . If any improvements, send me a new request ;)


function ReverseLookup($IPAddress)
    {
        # Variables
            global $ReverseLookupData;

            if ($ReverseLookupData[$IPAddress])
			{
				return $ReverseLookupData[$IPAddress];
			}
		else
			{
				return "";
			}
    }
function MakeToken()
    {
        # Variables
            global $MySQLConnection;
            global $LogInsightFQDN;
            global $LogInsightUserName;
            global $LogInsightPassword;
            global $LogInsightLoginProvider;
            $DataFields = json_encode(array("username" => $LogInsightUserName, "password" => $LogInsightPassword, "provider" => $LogInsightLoginProvider));   
        # Run CURL                                                                                                    
            $ch = curl_init("https://".$LogInsightFQDN."/api/v1/sessions");      
            curl_setopt($ch, CURLOPT_POSTFIELDS, $DataFields);			
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);			
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json')                                                                       
            );       

        # Result                                                                                                       
            $result = curl_exec($ch);

        # Decode and get Expiration
            $DataResult = json_decode($result, true);
            $TokenExpireTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +28 minutes'));
            $NewToken = $DataResult["sessionId"];
            
            # Check token
                if ($NewToken)
                    {
                        # Insert in database for forture use
                            $insert = mysqli_query($MySQLConnection,"INSERT INTO nsxlogger_token set token = '".$NewToken."', expire = '".$TokenExpireTime."'");

                            if (mysqli_connect_errno()) {
                                printf("Connect failed: %s\n", mysqli_connect_error());
                                exit();
                            }
                    }
        
        # Return token.
            return $NewToken;
    }
function GetToken()
    {
        # Variables
            global $MySQLConnection;
            
        # Get Token
            $SqlQuery = mysqli_query($MySQLConnection, "SELECT * FROM nsxlogger_token where expire > current_timestamp() order by id desc limit 1");
            $row = mysqli_fetch_assoc($SqlQuery);
            
        # Check if there is any token, if - return or else just make a new one
            $tokenID = $row["token"];
            if ($tokenID == "")
                {
                    return MakeToken();
                }
            else
                {
                    return $tokenID;
                }
    
    }
function SearchLog($timestamp = "86400000",$IP)
    {
        # Variables
            global $LogInsightLimit;
            global $LogInsightFQDN;
            $LogInsightToken = GetToken();
			$SearchData = "/appname/FIREWALL_PKTLOG/text/" . urlencode("CONTAINS ". $IP) ."/timestamp/LAST%20" . $timestamp;      

		# Curl Call against Loginsight
			$ch = curl_init("https://".$LogInsightFQDN."/api/v1/events".$SearchData."?view=simple&limit=".$LogInsightLimit."&order-by-direction=DESC&content-pack-fields=com.vmware.nsxt");                                                                      
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                                                                                       
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);			
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',     
				'Authorization: Bearer '.$LogInsightToken.'')                                                                       
			);                                                                                                                                                                                                             
	
        # Get result and print
            $result = curl_exec($ch);

        # Only show Results
            $JsonData = json_decode($result, true);
            $JsonDataResults = json_encode($JsonData["results"]);
        
        # Return data
            return $JsonDataResults;

    }		
?>