<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>Xin chào!</h1>
            <p>Bạn nhận được tin nhắn này vì chúng tôi nhận được yêu cầu đổi lại mật khẩu cho tài khoản của bạn</p>
            <a class="btn btn-link text-center" href="{{ url('/reset-password/'.$token) }}">Đổi mật khẩu</a>
            <br/>
            <p class="mb-3">Nếu bạn không yêu cầu đổi lại mật khẩu, vui lòng bỏ qua email này</p>
       </div>
   </div>
</div>