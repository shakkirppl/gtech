<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>GTech</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{url('admin/vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{url('admin/vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{url('admin/vendors/css/vendor.bundle.base.css')}}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{url('admin/css/vertical-layout-light/style.css')}}">
  <!-- endinject -->
</head>
<style>

 /* .container {
    position: relative;
    width: 50%;
    height: 50vh; 
  */

 /* .image {
    position: absolute;
    top: 50%;
    left: 60%;
    transform: translate(-50%, -50%);
    max-width: 100%;                   
     max-height: 100%;                 
}  */

.responsive{
   width: 100%;
  max-width: 150px;
  height: auto;
}


</style>
<body>
      @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div><br />
          @endif
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              
                <div class="image" style="text-align:center;">
               <img src="{{url('/admin/images/logo/login.avif')}}" alt="logo" class="responsive">
                </div>
           
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>

              <form  class="pt-3" method="POST" action="{{ route('login') }}">
            @csrf
           
                <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username" name="email">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password" name="password">
                </div>
                <div class="mt-3">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                  
                </div>
            
                
            
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{url('admin/vendors/js/vendor.bundle.base.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{url('admin/js/off-canvas.js')}}"></script>
  <script src="{{url('admin/js/hoverable-collapse.js')}}"></script>
  <script src="{{url('admin/js/template.js')}}"></script>
  <script src="{{url('admin/js/settings.js')}}"></script>
  <script src="{{url('admin/js/todolist.js')}}"></script>
  <!-- endinject -->
</body>

</html>
