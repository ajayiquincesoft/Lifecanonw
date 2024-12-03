<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">
	<?php 
	
		if(request()->is('/')){
			echo '<title>Best Life Coaching App - App for Life Coaches - Life Canon</title>';
			echo ' <meta name="description" content="Unlock your potential, achieve goals, and transform your life with our Life Coaching App. Download now for personal growth and success.">';
		}elseif(request()->is('about-us')){
			echo '<title> Life Coach App - App for Life Coaches - Life Canon</title>';
			echo '<meta name="description" content="Take charge of your life with our Life Coaching App. Set goals, find clarity, and unleash your true potential. Download now for a transformative journey.">';
		}else{
			echo '<title>Life Canon</title>';
			echo '<meta name="description" content="">';
			
		}
	
	?>
    

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/assets/front-assets/style.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/front-assets/custom.css')}}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css">
    <link rel="icon" href="{{asset('public/assets/front-assets/images/new-lifecanon-logo1.png')}}" width="32" height="32"/>
	<!--<link rel="icon" href="{{asset('public/assets/front-assets/images/new-fav.png')}}" />-->

    <!-- small header js start here -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/classie/1.0.1/classie.js"></script>
    <script>
        function init() {
            window.addEventListener('scroll', function(e) {
                var distanceY = window.pageYOffset || document.documentElement.scrollTop,
                    shrinkOn = 100,
                    header = document.querySelector("header");
                if (distanceY > shrinkOn) {
                    classie.add(header, "smaller");
                } else {
                    if (classie.has(header, "smaller")) {
                        classie.remove(header, "smaller");
                    }
                }
            });
        }
        window.onload = init();
    </script>
    <!-- small header js start here -->
	
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-68VYGFQ7WL"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'G-68VYGFQ7WL');
	</script>
	
</head>


<body>

    <header id="lf-canon-header">
        <div class="container">
            <div class="row">
                <div class="col-xl-1"></div>
                <div class="col-xl-2 col-lg-2 col-md-3">
				
				<a href="{{route('welcome')}}"><img class="logo" src="{{asset('public/assets/front-assets/images/LC-Logo-final.svg')}}" width="80" style="padding-bottom: 6px;"></a>
				
				<!--<a href="{{route('welcome')}}"><img class="logo" src="{{asset('public/assets/front-assets/images/LC-Canon-newlogo.png')}}" width="80" style="padding-bottom: 6px;"></a>-->
			
				
				</div>
                <div class="col-xl-6 col-lg-7 col-md-6">
                    <nav class="navbar navbar-expand-md">
                        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <div class="navbar-collapse collapse" id="navbarCollapse" style="">
                            <div class="mob-logo"><a href="{{route('welcome')}}"><img src="{{asset('public/assets/front-assets/images/mob-logo.png')}}" width="100%"></a></div>
                            <div class="nav-btn"><a class="sign-btn" href="{{route('user-signup')}}">Sign Up</a> <a class="log-btn" href="{{route('user-login')}}">Log In</a></div>
                            <ul class="navbar-nav">
                                <li><a class="nav-link" href="{{(URL::current() != url('/'))?url('/').'#fea-sec':'#fea-sec'}}">Features</a></li>
                                <li><a class="nav-link" href="{{(URL::current() != url('/'))?url('/').'#work-sec':'#work-sec'}}">How It Works</a></li>
                                <li><a class="nav-link" href="{{(URL::current() != url('/'))?url('/').'#cost-sec':'#cost-sec'}}">Price</a></li>
                                <li><a class="nav-link" href="{{(URL::current() != url('/'))?url('/').'#cust-sec':'#cust-sec'}}">Reviews</a></li>
                                <li><a class="nav-link" href="{{(URL::current() != url('/'))?url('/').'#ques-sec':'#ques-sec'}}">Contact Us</a></li>
                            </ul><!-- ul end here -->
                        </div><!-- navbar-collapse end here -->
                    </nav><!-- nav end here -->
                </div><!-- col end here -->

                <div class="col-xl-2 col-lg-3 col-md-3">
				@if(Auth::user())
					<a class="sign-btn" href="{{route('user.dashboard')}}">My Account</a>
				@else
					<a class="sign-btn" href="{{route('user-signup')}}">Sign Up</a> <a class="log-btn" href="{{route('user-login')}}">Log In</a>	
				@endif
				
				
				</div>
            </div><!-- row end here -->
        </div><!-- container end here -->
    </header><!-- header end here -->
    @yield('content')

 
    <footer id="ft-footer">
        <ul>
            <li><a class="nav-link" href="{{url('about-us')}}">About us</a></li>
            <li><a class="nav-link" href="{{route('contact-us')}}">Contact us</a></li>
            <li><a href="{{url('disclaimer')}}">Disclaimer</a></li>
            <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
            <li><a href="{{url('terms-conditions')}}">Terms & Conditions</a></li>
        </ul><!-- ul end here -->
		<!---------------- State wise service -------------->
		<div class="container">
			<div class="row">
				<div class="col-md-12">
				<div align="center">
					<a href="https://lp.lifecanon.com/servicesweprovide.htm" class="navigation" style="color:#000;font-size: 16px;font-weight: 600;">Services We Provide</a><br>
					<p align="center" style="color:#000;font-size: 13px;"> <strong>States We Service: &nbsp;</strong><a href="https://lp.lifecanon.com/areasweservice/state-alabama/" class="navigation" style="color:#000" data-wpel-link="internal">Alabama</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-arkansas" class="navigation" style="color:#000" data-wpel-link="internal">Arkansas</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-arizona" class="navigation" style="color:#000" data-wpel-link="internal">Arizona</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-california" class="navigation" style="color:#000" data-wpel-link="internal">California</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-colorado" class="navigation" style="color:#000" data-wpel-link="internal">Colorado</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-connecticut" class="navigation" style="color:#000" data-wpel-link="internal">Connecticut</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-district-of-columbia" class="navigation" style="color:#000" data-wpel-link="internal">District Of Columbia</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-florida" class="navigation" style="color:#000" data-wpel-link="internal">Florida</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-georgia" class="navigation" style="color:#000" data-wpel-link="internal">Georgia</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-hawaii" class="navigation" style="color:#000" data-wpel-link="internal">Hawaii</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-idaho" class="navigation" style="color:#000" data-wpel-link="internal">Idaho</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-illinois" class="navigation" style="color:#000" data-wpel-link="internal">Illinois</a>|
					<a href="https://lp.lifecanon.com/areasweservice/state-indiana" class="navigation" style="color:#000" data-wpel-link="internal">Indiana</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-iowa" class="navigation" style="color:#000" data-wpel-link="internal">Iowa</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-kansas" class="navigation" style="color:#000" data-wpel-link="internal">Kansas</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-kentucky" class="navigation" style="color:#000" data-wpel-link="internal">Kentucky</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-louisiana" class="navigation" style="color:#000" data-wpel-link="internal">Louisiana</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-maine" class="navigation" style="color:#000" data-wpel-link="internal">Maine</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-maryland" class="navigation" style="color:#000" data-wpel-link="internal">Maryland</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-massachusetts" class="navigation" style="color:#000" data-wpel-link="internal">Massachusetts</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-michigan" class="navigation" style="color:#000" data-wpel-link="internal">Michigan</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-minnesota" class="navigation" style="color:#000" data-wpel-link="internal">Minnesota</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-mississippi" class="navigation" style="color:#000" data-wpel-link="internal">Mississippi</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-missouri" class="navigation" style="color:#000" data-wpel-link="internal">Missouri</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-montana" class="navigation" style="color:#000" data-wpel-link="internal">Montana</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-nebraska" class="navigation" style="color:#000" data-wpel-link="internal">Nebraska</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-nevada" class="navigation" style="color:#000" data-wpel-link="internal">Nevada</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-new-hampshire" class="navigation" style="color:#000" data-wpel-link="internal">New Hampshire</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-new-jersey" class="navigation" style="color:#000" data-wpel-link="internal">New Jersey</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-new-mexico" class="navigation" style="color:#000" data-wpel-link="internal">New Mexico</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-new-york" class="navigation" style="color:#000" data-wpel-link="internal">New York</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-north-carolina" class="navigation" style="color:#000" data-wpel-link="internal">North Carolina</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-north-dakota" class="navigation" style="color:#000" data-wpel-link="internal">North Dakota</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-ohio" class="navigation" style="color:#000" data-wpel-link="internal">Ohio</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-oklahoma" class="navigation" style="color:#000" data-wpel-link="internal">Oklahoma</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-oregon" class="navigation" style="color:#000" data-wpel-link="internal">Oregon</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-pennsylvania" class="navigation" style="color:#000" data-wpel-link="internal">Pennsylvania</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-south-carolina" class="navigation" style="color:#000" data-wpel-link="internal">South Carolina</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-south-dakota" class="navigation" style="color:#000" data-wpel-link="internal">South Dakota</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-tennessee" class="navigation" style="color:#000" data-wpel-link="internal">Tennessee</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-texas" class="navigation" style="color:#000" data-wpel-link="internal">Texas</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-utah" class="navigation" style="color:#000" data-wpel-link="internal">Utah</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-vermont" class="navigation" style="color:#000" data-wpel-link="internal">Vermont</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-virginia" class="navigation" style="color:#000" data-wpel-link="internal">Virginia</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-washington" class="navigation" style="color:#000" data-wpel-link="internal">Washington</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-west-virginia" class="navigation" style="color:#000" data-wpel-link="internal">West Virginia</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-wisconsin" class="navigation" style="color:#000" data-wpel-link="internal">Wisconsin</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-wyoming" class="navigation" style="color:#000" data-wpel-link="internal">Wyoming</a></p>
				</div>
				
					<!--<div align=""><a href="https://lp.lifecanon.com/servicesweprovide.htm" class="navigation" style="color:#000;font-size: 16px;font-weight: 600;">Services We Provide</a><br>
					<p align="center" style="color:#000;font-size: 13px;"> <strong>States We Service: &nbsp;</strong><a href="https://lp.lifecanon.com/areasweservice/state-alabama/" class="navigation" style="color:#000" data-wpel-link="internal">Alabama</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-arkansas" class="navigation" style="color:#000" data-wpel-link="internal">Arkansas</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-arizona" class="navigation" style="color:#000" data-wpel-link="internal">Arizona</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-california" class="navigation" style="color:#000" data-wpel-link="internal">California</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-colorado" class="navigation" style="color:#000" data-wpel-link="internal">Colorado</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-connecticut" class="navigation" style="color:#000" data-wpel-link="internal">Connecticut</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-district-of-columbia" class="navigation" style="color:#000" data-wpel-link="internal">District Of Columbia</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-florida" class="navigation" style="color:#000" data-wpel-link="internal">Florida</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-georgia" class="navigation" style="color:#000" data-wpel-link="internal">Georgia</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-hawaii" class="navigation" style="color:#000" data-wpel-link="internal">Hawaii</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-idaho" class="navigation" style="color:#000" data-wpel-link="internal">Idaho</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-illinois" class="navigation" style="color:#000" data-wpel-link="internal">Illinois</a>|
					<a href="https://lp.lifecanon.com/areasweservice/state-indiana" class="navigation" style="color:#000" data-wpel-link="internal">Indiana</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-iowa" class="navigation" style="color:#000" data-wpel-link="internal">Iowa</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-kansas" class="navigation" style="color:#000" data-wpel-link="internal">Kansas</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-kentucky" class="navigation" style="color:#000" data-wpel-link="internal">Kentucky</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-louisiana" class="navigation" style="color:#000" data-wpel-link="internal">Louisiana</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-maine" class="navigation" style="color:#000" data-wpel-link="internal">Maine</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-maryland" class="navigation" style="color:#000" data-wpel-link="internal">Maryland</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-massachusetts" class="navigation" style="color:#000" data-wpel-link="internal">Massachusetts</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-michigan" class="navigation" style="color:#000" data-wpel-link="internal">Michigan</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-minnesota" class="navigation" style="color:#000" data-wpel-link="internal">Minnesota</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-mississippi" class="navigation" style="color:#000" data-wpel-link="internal">Mississippi</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-mississippi" class="navigation" style="color:#000" data-wpel-link="internal">Missouri</a>
					<a href="https://lp.lifecanon.com/areasweservice/state-montana" class="navigation" style="color:#000" data-wpel-link="internal">Montana</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-nebraska" class="navigation" style="color:#000" data-wpel-link="internal">Nebraska</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-nevada" class="navigation" style="color:#000" data-wpel-link="internal">Nevada</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-new-hampshire" class="navigation" style="color:#000" data-wpel-link="internal">New Hampshire</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-new-jersey" class="navigation" style="color:#000" data-wpel-link="internal">New Jersey</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-new-mexico" class="navigation" style="color:#000" data-wpel-link="internal">New Mexico</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-new-york" class="navigation" style="color:#000" data-wpel-link="internal">New York</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-north-carolina" class="navigation" style="color:#000" data-wpel-link="internal">North Carolina</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-north-dakota" class="navigation" style="color:#000" data-wpel-link="internal">North Dakota</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-ohio" class="navigation" style="color:#000" data-wpel-link="internal">Ohio</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-oklahoma" class="navigation" style="color:#000" data-wpel-link="internal">Oklahoma</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-oregon" class="navigation" style="color:#000" data-wpel-link="internal">Oregon</a>
					<a href="https://lp.lifecanon.com/areasweservice/state-pennsylvania" class="navigation" style="color:#000" data-wpel-link="internal">Pennsylvania</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-south-carolina" class="navigation" style="color:#000" data-wpel-link="internal">South Carolina</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-south-dakota" class="navigation" style="color:#000" data-wpel-link="internal">South Dakota</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-tennessee" class="navigation" style="color:#000" data-wpel-link="internal">Tennessee</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-texas" class="navigation" style="color:#000" data-wpel-link="internal">Texas</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-utah" class="navigation" style="color:#000" data-wpel-link="internal">Utah</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-vermont" class="navigation" style="color:#000" data-wpel-link="internal">Vermont</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-virginia" class="navigation" style="color:#000" data-wpel-link="internal">Virginia</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-washington" class="navigation" style="color:#000" data-wpel-link="internal">Washington</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-west-virginia" class="navigation" style="color:#000" data-wpel-link="internal">West Virginia</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-wisconsin" class="navigation" style="color:#000" data-wpel-link="internal">Wisconsin</a> |
					<a href="https://lp.lifecanon.com/areasweservice/state-wyoming" class="navigation" style="color:#000" data-wpel-link="internal">Wyoming</a></p>
					</div>	--->
				</div>
			</div>
		</div>
		<br/>
		<!--------------- State wise service -------------->
		<div class="container">
		<div class="row">
			<div class="col-md-6">
				<p>©Life Canon {{date('Y')}}. All rights reserved.</p>
				<p>Designed by Poul Norholm</p>
			</div>
			<div class="col-md-6">
			<script type="text/javascript" src="https://seal-santabarbara.bbb.org/inc/legacy.js"></script><a href="https://www.bbb.org/us/ca/pismo-beach/profile/life-coach/pen-concepts-llc-1236-92072608/#sealclick" id="bbblink" class="ruhzbul" target="_blank" rel="nofollow"><img src="https://seal-santabarbara.bbb.org/logo/ruhzbul/bbb-92072608.png" style="border: 0;" alt="PEN Concepts LLC BBB Business Review" /></a>
			</div>
		</div>
		</div>
    </footer><!-- footer end here -->


    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            jQuery("#our-sec").owlCarousel({
                items: 3,
				
                responsive: {
                    992: {
                        items: 3
                    },
                    768: {
                        items: 2
                    },
                    640: {
                        items: 2
                    },
                    320: {
                        items: 1
                    }
                },
                loop: true,
                autoplay: true,
				autoplaySpeed:2000,
            })
        });
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 2000,
        });
    </script>
    @yield('script')
</body>

</html>