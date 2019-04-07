<footer class="footer">
    <div class="container">

        @if(!empty($location))
            <p class="float-left" style="margin-left: 20px"> 访问来自: {{$location}}</p>
        @endif
        {{--<p class="float-left">--}}
            {{--Powered By<a href="#">  MamerBBS </a><span style="color: #e27575;font-size: 14px;">❤</span>--}}
        {{--</p>--}}
        <p class="float-right"><a href="mailto:{{ setting('contact_email') }}">联系我们</a></p>
    </div>
</footer>
