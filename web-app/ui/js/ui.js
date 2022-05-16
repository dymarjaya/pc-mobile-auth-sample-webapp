/**
 * Function to send request to the back-end to create a PC user
 *    back-end will create a user and will return PC user's QR-code and user-id
 */
function create_pc_user_with_qr() {
    var data = new FormData();
    $.ajax({
        type: "POST",
        url: '../backend/pers/qr/create_pc_user_qr.php', // back-end url to call
        async: true,
        cache: false,
        dataType: 'json',
        data: data,
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        success: function(data, textStatus) {
            console.log("create_pc_user: success");

            // show user's QR-code and user-id on the web-page
            $("#pc_user_id").html(data.user_id);
            $("#pc_user_qr").attr("src", "data:image/gif;base64," + data.user_qr);
            $("#created_user_qr").attr("style", "display: block;")
        },

        error: function(data, textStatus, errorThrown) {
            console.log("create_pc_user error: " + data + "\n" + textStatus + "\n" + errorThrown);
        }
    });
}

/**
 * Function to send request to the back-end to create an Alias
 *    back-end will create an Alias and will return it with Activation Code
 * 
 * !!! WARNING - we create Activation Code here for DEMO PURPOSES ONLY
 *     You should create Activation Code at the moment when you requests key JSON from PC Server
 *     After you have created Activation Code you should send it to a user with another channel
 *     It can be email, SMS, push or something else
 * 
 *     We can not send SMS or something here in demo, that's why we create activation code here
 */
function create_alias() {
    var data = new FormData();
    $.ajax({
        type: "POST",
        url: '../backend/pers/alias/create_alias.php', // back-end url to call
        async: true,
        cache: false,
        dataType: 'json',
        data: data,
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        success: function(data, textStatus) {
            console.log("create_alias: success");

            // show user's QR-code and user-id on the web-page
            $("#alias").html(data.alias);
            $("#activation_code").html(data.activation_code);
            $("#created_user_alias").attr("style", "display: block;")
        },

        error: function(data, textStatus, errorThrown) {
            console.log("create_alias error: " + data + "\n" + textStatus + "\n" + errorThrown);
        }
    });     
}

/**
 * Function to send request to the sign in from web to back-end
 *    and will return transaction id
 */
function transaction_login() {
    $(".loader").show();
    var data = new FormData();
    var data = $("#user_id").val();
  
    if (data == null || data == "") {
      alert("Alias is required");
      return;
    }
  
    $.ajax({
      type: "POST",
      url: "../backend/login/start_authentication.php",
      async: true,
      cache: false,
      dataType: "json",
      // data: data,
      data: JSON.stringify({ alias: data }),
      processData: false, // tell jQuery not to process the data
      contentType: "application/json",
      success: function (data, textStatus) {
        console.log("login_transaction: success");
        $("#transaction_id").html(data.transaction_id);
        $("#logintransaction").attr("style", "display: block;");
        check_confirmation();
      },
  
      error: function (data, textStatus, errorThrown) {
        console.log("error: " + data + "\n" + textStatus + "\n" + errorThrown);
      },
    });
  }
  
/**
 * Function to check confirmation periodically of transaction status
 *    if transaction status id confirmed, then stop check and show result
 *    Successfully authenticated
 */
  function check_confirmation() {
    var datax = new FormData();
    var datax = $("#user_id").val();
    var xhr = new XMLHttpRequest();
    let data = JSON.stringify({ alias: datax });
    let response;
    xhr.open("POST", "../backend/login/finish_authentication.php");
    xhr.setRequestHeader("Content-Type", "application/json; charset=utf-8");
    xhr.setRequestHeader("Cache-Control", "no-cache");
    xhr.setRequestHeader("Pragma", "no-cache");
    xhr.send(data);
    xhr.onload = function (e) {
      if (this.status === 200 || this.status === 201) {
        response = JSON.parse(e.target.response);
        console.log(response);
  
        console.log("check_confirmation: success");
        $(".loader").hide();
        $("#authentication_successfull").html(
          response.authentication_successfull
        );
  
        if (response.authentication_successfull == true) {
          close_results();
          $("#show_result").html(
            '<b><span style="color:green">Successfully authenticated</span></b>'
          );
        } else {
          setTimeout(check_confirmation, 1000);
          $(".loader").show();
          // $("#show_result").html(
          //   '<b><span style="color:red">Authentication was not finished</span></b>'
          // );
        }
      } else {
        console.log(e.target.onerror);
      }
    };
  }
  
/**
 * Function to clear timeout check confirmation
 */
  function close_results() {
    clearTimeout(check_confirmation);
  }
  