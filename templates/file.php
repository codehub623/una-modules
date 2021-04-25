<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<style>
  th {
    text-align: center;
  }

  .button {
    cursor: pointer;
    background-color: #43dde6;
    margin-top: 20px;
  }

  .action {
    width: 50px;
  }
</style>
<?php

// echo '<pre>' . var_export($html_data_accounts, true) . '</pre>';


?>

<div class="container-fluid">
  <div style="margin-bottom: 20px;">
    <button class="btn button" style="float:right" id="add">Add</button>
    <label for="files" class="btn button" style="float:right;margin-right:20px">Import CSV File</label>
    <input class="btn button" id="submit" type="submit" style="float:right;margin-right:20px" disabled value="Submit">
    <input id="files" style="visibility:hidden;" type="file" accept=".csv">
    <h1>Table</h1>
  </div>
  <br />
  <form hidden>
    <div class="form-group">
      <!-- <label>Name:</label> -->
      <input type="text" name="name" class="form-control" placeholder="Name" required>
    </div>

    <div class="form-group">
      <!-- <label>Email:</label> -->
      <input type="text" name="email" class="form-control" placeholder="Email" required>
    </div>

    <div class="form-group">
      <!-- <label>Email:</label> -->
      <input type="text" name="brand" class="form-control" placeholder="Brand" required>
    </div>

    <div class="form-group">
      <!-- <label>Market:</label> -->
      <input type="text" name="market" class="form-control" placeholder="Marketplace">
    </div>

    <div class="form-group">
      <!-- <label>Store:</label> -->
      <input type="text" name="store" class="form-control" placeholder="Store">
    </div>

    <div class="form-group">
      <!-- <label>Flickr:</label> -->
      <input type="text" name="flickr" class="form-control" placeholder="Flickr">
    </div>

    <div class="form-group">
      <!-- <label>Instagram:</label> -->
      <!-- <input type="text" name="instagram" class="form-control" placeholder="Instagram"> -->
    </div>

    <div class="form-group">
      <!-- <label>Facebook:</label> -->
      <!-- <input type="text" name="facebook" class="form-control" placeholder="Facebook"> -->
    </div>

    <div class="form-group">
      <!-- <label>Facebook:</label> -->
      <input type="text" name="accountId" style="visibility: hidden;">
    </div>

    <button type="submit" class="btn btn-success save-btn">Save</button>

  </form>
  <br />

  <div class="table-responsive">
    <table class="table table-bordered data-table text-center ">
      <thead>
        <th>Name</th>
        <th>Email</th>
        <th>Brand</th>
        <th>Market</th>
        <th>Store</th>
        <th>Flickr</th>
        <th width="200px">Action</th>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>

  <div style="margin-top: 20px;">
    <button class="btn button" style="float:right" id="invite">Send Invitation Link</button>
  </div>

</div>

<script type="text/javascript">
  let data = <?php echo json_encode($html_data_accounts) ?>;
  let accountId = <?php echo $sAcountId ?>;
  let created_result = <?php echo json_encode($created_result) ?>;
  let test = <?php echo json_encode($test) ?>;

  console.log(data, accountId, created_result, test);
  console.log(test);

  $("input[name='accountId']").val(accountId);

  // accountId
  for (let i in data) {
    let id = data[i].id;
    let name = data[i].name;
    let email = data[i].email;
    let brand = data[i].brand;
    let market = data[i].market;
    let store = data[i].store;
    let flickr = data[i].flickr;
    // let instagram = data[i].instagram;
    // let facebook = data[i].facebook;
    $(".data-table tbody").append("<tr data-id='" + id + "' data-name='" + name + "' data-email='" + email + "' data-brand='" + brand + "' data-store='" + store + "' data-market='" + market + "' data-flickr='" + flickr + "'><td class='align-middle'>" + name + "</td><td class='align-middle'>" + email + "</td><td class='align-middle'>" + brand + "</td><td class='align-middle'>" + market + "</td><td class='align-middle'>" + store + "</td><td class='align-middle'>" + flickr + "</td><td><button class='btn btn-info btn-xs btn-edit action' >Edit</button><button class='btn btn-danger btn-xs btn-delete action'>Delete</button></td></tr>");
  }


  $("form").submit(function(e) {
    e.preventDefault();
    let name = $("input[name='name']").val();
    let email = $("input[name='email']").val();
    let brand = $("input[name='brand']").val();
    let market = $("input[name='market']").val();
    let store = $("input[name='store']").val();
    let flickr = $("input[name='flickr']").val();
    let data = {
      name,
      email,
      brand,
      market,
      store,
      flickr
    }
    $.ajax({
      url: "api/create.php",
      type: "POST",
      data: `data=${JSON.stringify(data)}&accountId=${accountId}`,
      beforeSend: function() {
        console.log('create Sending...')
      },
      success: function(data) {
        data = JSON.parse(data);
        console.log(data);
        if (data.success == true) {
          let id = data.id;
          $(".data-table tbody").append("<tr data-id='" + id + "' data-name='" + name + "' data-email='" + email + "' data-brand='" + brand + "' data-store='" + store + "' data-market='" + market + "' data-flickr='" + flickr + "'><td class='align-middle'>" + name + "</td><td class='align-middle'>" + email + "</td><td class='align-middle'>" + brand + "</td><td class='align-middle'>" + market + "</td><td class='align-middle'>" + store + "</td><td class='align-middle'>" + flickr + "</td><td><button class='btn btn-info btn-xs btn-edit action' >Edit</button><button class='btn btn-danger btn-xs btn-delete action'>Delete</button></td></tr>");
        }
      },
      error: function(e) {
        console.log('Failed')
      }
    });



    $("input[name='name']").val('');
    $("input[name='email']").val('');
    $("input[name='brand']").val('');
    $("input[name='market']").val('');
    $("input[name='store']").val('');
    $("input[name='flickr']").val('');
    $("form").toggle();
  });

  $("body").on("click", "#add", function() {
    $("form").toggle();
  });

  $("body").on("dblclick", "tbody tr", function() {
    console.log($(this).find(".btn-cancel").length == 1)
    if ($(this).find(".btn-cancel").length == 1) {
      $(this).find(".btn-cancel").click();
    } else {
      $(this).find(".btn-edit").click();
    }
  });

  $("body").on("click", ".btn-delete", function() {
    let t = this
    $(this).parents("tr").remove();
    let id = $(this).parents("tr").attr('data-id');
    let email = $(this).parents("tr").attr('data-email');
    let data = {
      id,
      email
    }

    $.ajax({
      url: "api/delete.php",
      type: "POST",
      data: `data=${JSON.stringify(data)}&accountId=${accountId}`,
      beforeSend: function() {
        console.log('Sending...')
      },
      success: function(data) {
        data = JSON.parse(data);
        console.log(data);
        if (data.success == true) {
          $(t).parents("tr").remove();
        }
      },
      error: function(e) {
        console.log('Failed')
      }
    });
  });

  $("body").on("click", ".btn-edit", function() {
    var name = $(this).parents("tr").attr('data-name');
    var email = $(this).parents("tr").attr('data-email');
    var brand = $(this).parents("tr").attr('data-brand');
    var store = $(this).parents("tr").attr('data-store');
    var market = $(this).parents("tr").attr('data-market');
    var flickr = $(this).parents("tr").attr('data-flickr');

    $(this).parents("tr").find("td:eq(0)").html('<input style="width:100%" name="edit_name" value="' + name + '">');
    $(this).parents("tr").find("td:eq(1)").html('<input style="width:100%" name="edit_email" value="' + email + '">');
    $(this).parents("tr").find("td:eq(2)").html('<input style="width:100%" name="edit_brand" value="' + brand + '">');
    $(this).parents("tr").find("td:eq(3)").html('<input style="width:100%" name="edit_market" value="' + market + '">');
    $(this).parents("tr").find("td:eq(4)").html('<input style="width:100%" name="edit_store" value="' + store + '">');
    $(this).parents("tr").find("td:eq(5)").html('<input style="width:100%" name="edit_flickr" value="' + flickr + '">');

    $(this).parents("tr").find("td:eq(6)").prepend("<button class='btn btn-info btn-xs btn-update'>Update</button><button class='btn btn-warning btn-xs btn-cancel'>Cancel</button>")
    $(this).hide();
  });

  $("body").on("click", ".btn-cancel", function() {
    var name = $(this).parents("tr").attr('data-name');
    var email = $(this).parents("tr").attr('data-email');
    var brand = $(this).parents("tr").attr('data-brand');
    var store = $(this).parents("tr").attr('data-store');
    var market = $(this).parents("tr").attr('data-market');
    var flickr = $(this).parents("tr").attr('data-flickr');

    $(this).parents("tr").find("td:eq(0)").text(name);
    $(this).parents("tr").find("td:eq(1)").text(email);
    $(this).parents("tr").find("td:eq(2)").text(brand);
    $(this).parents("tr").find("td:eq(3)").text(market);
    $(this).parents("tr").find("td:eq(4)").text(store);
    $(this).parents("tr").find("td:eq(5)").text(flickr);

    $(this).parents("tr").find(".btn-edit").show();
    $(this).parents("tr").find(".btn-update").remove();
    $(this).parents("tr").find(".btn-cancel").remove();
  });

  $("body").on("click", "#invite", function() {
    let data = [];
    $('body').find("tr").map(t => {
      let id = $($('body').find("tr")[t]).attr("data-id") * 1;
      let email = $($('body').find("tr")[t]).attr("data-email");
      let name = $($('body').find("tr")[t]).attr("data-name");
      if (t) data.push({
        id,
        email,
        name
      })
    })
    data = JSON.stringify(data)
    $.ajax({
      url: "api/sendverification.php",
      type: "POST",
      data: `data=${data}`,
      beforeSend: function() {
        console.log('Sending...')
      },
      success: function(data) {
        data = JSON.parse(data);
        console.log(data);
        if (data.success == true) {

        }
      },
      error: function(e) {
        console.log('Failed')
      }
    });

  })
  $("body").on("click", ".btn-update", function() {
    let t = this;
    var id = $(this).parents("tr").attr('data-id');
    var name = $(this).parents("tr").find("input[name='edit_name']").val();
    var email = $(this).parents("tr").find("input[name='edit_email']").val();
    var brand = $(this).parents("tr").find("input[name='edit_brand']").val();
    var market = $(this).parents("tr").find("input[name='edit_market']").val();
    var store = $(this).parents("tr").find("input[name='edit_store']").val();
    var flickr = $(this).parents("tr").find("input[name='edit_flickr']").val();
    let data = {
      id,
      name,
      email,
      brand,
      market,
      store,
      flickr,
    }
    $.ajax({
      url: "api/update.php",
      type: "POST",
      data: `data=${JSON.stringify(data)}&accountId=${accountId}`,
      beforeSend: function() {
        console.log('Sending...')
      },
      success: function(data) {
        data = JSON.parse(data);
        console.log(data);
        if (data.success == true) {

          $(t).parents("tr").find("td:eq(0)").text(name);
          $(t).parents("tr").find("td:eq(1)").text(email);
          $(t).parents("tr").find("td:eq(2)").text(brand);
          $(t).parents("tr").find("td:eq(3)").text(market);
          $(t).parents("tr").find("td:eq(4)").text(store);
          $(t).parents("tr").find("td:eq(5)").text(flickr);

          $(t).parents("tr").attr('data-name', name);
          $(t).parents("tr").attr('data-email', email);
          $(t).parents("tr").attr('data-brand', brand);
          $(t).parents("tr").attr('data-market', market);
          $(t).parents("tr").attr('data-store', store);
          $(t).parents("tr").attr('data-flickr', flickr);
          $(t).parents("tr").find(".btn-edit").show();
          $(t).parents("tr").find(".btn-cancel").remove();
          $(t).parents("tr").find(".btn-update").remove();

        }
      },
      error: function(e) {
        console.log('Failed')
      }
    });
  });


  $(document).ready(function() {
    $("#files").change(function() {
      if (this.files.length > 0 && this.files[0].type == "application/vnd.ms-excel") {
        filename = this.files[0].name;
        if (filename.length > 10) {
          filename = filename.slice(0, 7) + "...";
        }
        $("#submit").attr("disabled", false);
        $("#submit").attr("value", `Submit (File: ${filename})`);
      } else {
        formatSubmit();
      }
    });
    $("#submit").on('click', (function(e) {
      file = $('#files').get(0).files[0];
      if (!file) return
      formdata = new FormData();
      formdata.append("csv", file);
      $.ajax({
        url: "api/ajaxfileupload.php",
        type: "POST",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
          console.log('Sending...')
          $('#files').val('')
        },
        success: function(data) {
          data = JSON.parse(data);
          if (data.success == true) {
            console.log(data);
            alert('Error');
          } else {
            alert('Error');
          }
        },
        error: function(e) {
          console.log('Failed')
          formatSubmit();
        }
      });
    }));

    function formatSubmit() {
      $("#submit").attr("disabled", true);
      $("#submit").attr("value", 'Submit (Fil: inValid)');
    }
  })
</script>