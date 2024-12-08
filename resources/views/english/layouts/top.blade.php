<div class="header d-flex justify-content-between align-items-center">
    <p class="">Welcome to <br>
        Our Printing Shop</p>
    <img src="{{ asset('public/assets/arabic') }}/img/logo.png" alt="" class="logo ms-5">
    <div class="contact contactPc d-flex justify-content-end align-items-right text-right ">
        <p class="text-end"> Customer Support <br>
            123 456 789</p>
       {{--  <img src="{{ asset('public/assets/arabic') }}/img/contact.png" alt=""> --}}
       <img src="{{ url('public/assets/english/img/contact.png') }}" alt="contact-us">

    </div>
    <div class="Contact dropdown">
        <img src="{{ asset('public/assets/english') }}/img/contact.png" alt="" class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <ul class="dropdown-menu">
            <p class="text-end"> Customer Support <br>
                123 456 789</p>
        </ul>
      </div>
</div>
