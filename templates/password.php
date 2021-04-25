<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<style>
  .pass_show {
    position: relative
  }

  .pass_show .ptxt {

    position: absolute;

    top: 50%;

    right: 10px;

    z-index: 1;

    color: #f36c01;

    margin-top: -10px;

    cursor: pointer;

    transition: .3s ease all;

  }

  .pass_show .ptxt:hover {
    color: #333333;
  }

  .card {
    width: 30%;
    margin: auto;
    margin-top: 150px;
  }

  button {
    margin-top: 30px;
  }
</style>

<body>
  <div class="d-flex justify-content-center align-items-center">
    <div class="card">
      <!-- <h4 class="text-center text-primary">Welcome to UNA</h4> -->
      <label>New Password</label>
      <div class="form-group pass_show">
        <input type="password" name='password' class="form-control" placeholder="New Password">
      </div>
      <label>Confirm Password</label>
      <div class="form-group pass_show">
        <input type="password" name='password_confirmed' class="form-control" placeholder="Confirm Password">
      </div>
      <button class="btn btn-block btn-primary">Submit</button>

    </div>
  </div>

</body>


<script>
  $(document).ready(function() {
    $('.pass_show').append('<span class="ptxt">Show</span>');
  });


  $(document).on('click', '.pass_show .ptxt', function() {

    $(this).text($(this).text() == "Show" ? "Hide" : "Show");

    $(this).prev().attr('type', function(index, attr) {
      return attr == 'password' ? 'text' : 'password';
    });

  });
  $('body').on('click', 'button', function() {
    console.log('button')
    let urlParams = new URLSearchParams(window.location.search);
    let gettoken = urlParams.get('token');
    console.log(gettoken)
    password = $("input[name='password']").val();
    password_confirmed = $("input[name='password_confirmed']").val();
    console.log(password_confirmed,password)
    if (password != password_confirmed) {
      alert('Your password and confirmation password do not match!')
      return
    }
    let data = {
      password,
      token:gettoken
    }
    data = JSON.stringify(data)
    $.ajax({
      url: "api/setPassword.php",
      type: "POST",
      data: `data=${data}`,
      beforeSend: function() {
        console.log('Sending...')
      },
      success: function(data) {
        data = JSON.parse(data);
        console.log(data);
        if (data.success == true) {
          var getUrl = window.location;
          window.location.href = `/${getUrl.pathname.split('/')[1]}/page/login`;
        } else {
          alert('Sorry. Token Valid time was expired.')
        }
      },
      error: function(e) {
        console.log('Failed')
      }
    });
  })
</script>