@extends('layouts.sitelayout')

@section('content')
    <!-- breadcrumb start-->
<section class="breadcrumb breadcrumb_bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb_iner text-center">
                    <div class="breadcrumb_iner_item">
                        <h2>
                            Practice
                        </h2>
                        <p>
                            Home
                            <span>
                                /
                            </span>
                            Practice
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- breadcrumb start-->
<!-- feature_part start-->
<section class="feature_part single_feature_padding" style="padding-top: 60px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <div class="section_tittle text-center" style="margin-bottom: 60px;">
                    <p>
                        popular Papers
                    </p>
                    <h2>
                        Practice Previous Year Papers
                    </h2>
                </div>
            </div>
        </div>
    </div>
     <div class="container">
        <div class="row">
            <!-- partial:index.partial.html -->
            <section class="grid" data-state="section1">
                <nav class="btns" role="tablist" aria-label="Tabs">
                   <button role="tab" aria-selected="true" aria-controls="panel-1" id="tab-1" tabindex="0" data-setstate="section1">
                        Past Year Pages - I
                    </button>
                    <!-- <button role="tab" aria-selected="false" aria-controls="panel-2" id="tab-2" tabindex="-1" data-setState="section2">
                        Past Year Pages - II
                    </button>
                    <button role="tab" aria-selected="false" aria-controls="panel-3" id="tab-3" tabindex="-1" data-setState="section3">
                        Past Year Pages - III
                    </button> -->
                </nav>
                <article id="panel-1" role="tabpanel" tabindex="0" aria-labelledby="tab-1" data-scene="section1" >
                    <!-- partial:index.partial.html -->
                                <div class="tabbable-panel">
                                    <div class="tabbable-line">
                                        <ul class="nav nav-tabs ">
                                            <li class="active">
                                                <a href="#tab_default_1" data-toggle="tab">
                                                    2020
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#tab_default_2" data-toggle="tab">
                                                    2019
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#tab_default_3" data-toggle="tab">
                                                    2018
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#tab_default_4" data-toggle="tab">
                                                    2017
                                                </a>
                                            </li>
                                            
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_default_1">
                                                <div class="container">
                                                    <div class="card-group vgr-cards">
                                                        <div class="card">
                                                            <div class="card-img-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <img class="card-img" src="{{themes('site/img/file-00.png')}}" alt="Card image cap" style="width:50px">
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <h4 class="card-title" style="padding-top:15px;">
                                                                            JEE Main 2020 Online(7th May Morning)
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="#" class="btn_1 text-uppercase rt" >
                                                                            Take Exam
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <div class="card-group vgr-cards">
                                                        <div class="card">
                                                            <div class="card-img-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <img class="card-img" src="{{themes('site/img/file-00.png')}}" alt="Card image cap" style="width:50px">
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <h4 class="card-title" style="padding-top:15px;">
                                                                            JEE Main 2020 Online(7th May Morning)
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="#" class="btn_1 text-uppercase rt" >
                                                                            Take Exam
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <div class="card-group vgr-cards">
                                                        <div class="card">
                                                            <div class="card-img-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <img class="card-img" src="{{themes('site/img/file-00.png')}}" alt="Card image cap" style="width:50px">
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <h4 class="card-title" style="padding-top:15px;">
                                                                            JEE Main 2020 Online(7th May Morning)
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="#" class="btn_1 text-uppercase rt" >
                                                                            Take Exam
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <div class="card-group vgr-cards">
                                                        <div class="card">
                                                            <div class="card-img-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <img class="card-img" src="{{themes('site/img/file-00.png')}}" alt="Card image cap" style="width:50px">
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <h4 class="card-title" style="padding-top:15px;">
                                                                            JEE Main 2020 Online(7th May Morning)
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="#" class="btn_1 text-uppercase rt" >
                                                                            Take Exam
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <div class="card-group vgr-cards">
                                                        <div class="card">
                                                            <div class="card-img-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <img class="card-img" src="{{themes('site/img/file-00.png')}}" alt="Card image cap" style="width:50px">
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <h4 class="card-title" style="padding-top:15px;">
                                                                            JEE Main 2020 Online(7th May Morning)
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="#" class="btn_1 text-uppercase rt" >
                                                                            Take Exam
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_default_2">
                                                <div class="container">
                                                    <div class="card-group vgr-cards">
                                                        <div class="card">
                                                            <div class="card-img-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <img class="card-img" src="{{themes('site/img/file-00.png')}}" alt="Card image cap" style="width:50px">
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <h4 class="card-title" style="padding-top:15px;">
                                                                            JEE Main 2019 Online(7th May Morning)
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="#" class="btn_1 text-uppercase rt" >
                                                                            Take Exam
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <div class="card-group vgr-cards">
                                                        <div class="card">
                                                            <div class="card-img-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <img class="card-img" src="{{themes('site/img/file-00.png')}}" alt="Card image cap" style="width:50px">
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <h4 class="card-title" style="padding-top:15px;">
                                                                            JEE Main 2020 Online(7th May Morning)
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="#" class="btn_1 text-uppercase rt" >
                                                                            Take Exam
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <div class="card-group vgr-cards">
                                                        <div class="card">
                                                            <div class="card-img-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <img class="card-img" src="{{themes('site/img/file-00.png')}}" alt="Card image cap" style="width:50px">
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <h4 class="card-title" style="padding-top:15px;">
                                                                            JEE Main 2020 Online(7th May Morning)
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="#" class="btn_1 text-uppercase rt" >
                                                                            Take Exam
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_default_3">
                                                <div class="container">
                                                    <div class="card-group vgr-cards">
                                                        <div class="card">
                                                            <div class="card-img-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <img class="card-img" src="{{themes('site/img/file-00.png')}}" alt="Card image cap" style="width:50px">
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <h4 class="card-title" style="padding-top:15px;">
                                                                            JEE Main 2018 Online(7th May Morning)
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="#" class="btn_1 text-uppercase rt" >
                                                                            Take Exam
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_default_4">
                                                <div class="container">
                                                    <div class="card-group vgr-cards">
                                                        <div class="card">
                                                            <div class="card-img-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <img class="card-img" src="{{themes('site/img/file-00.png')}}" alt="Card image cap" style="width:50px">
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <h4 class="card-title" style="padding-top:15px;">
                                                                            JEE Main 2017 Online(7th May Morning)
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="#" class="btn_1 text-uppercase rt" >
                                                                            Take Exam
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    <!-- partial -->
                </article>

               <!--  <article id="panel-2" role="tabpanel" tabindex="0" aria-labelledby="tab-2" data-scene="section2">
                    <p>EVERY CHILD YEARNS TO LEARN
Making Your Childs World Better
Replenish seasons may male hath fruit beast were seas saw you arrie said man beast whales his void unto last session for bite. Set have great you'll male grass yielding yielding man</p>
                </article>
                <article id="panel-3" role="tabpanel" tabindex="0" aria-labelledby="tab-3" data-scene="section3">
                    <p>EVERY CHILD YEARNS TO LEARN
Making Your Childs World Better
Replenish seasons may male hath fruit beast were seas saw you arrie said man beast whales his void unto last session for bite. Set have great you'll male grass yielding yielding man</p>
                </article> -->
            </section>
            <!-- partial -->
        </div>
    </div>
</section>
<!-- upcoming_event part start-->

<script>
    console.clear();
    const grid = document.querySelector('.grid');
    const tabs = document.querySelectorAll('[role="tab"]');
    const tabList = document.querySelector('[role="tablist"]');
    let tabFocus = 0;
    for (const [index, tab] of tabs.entries()) {
    tab.style.setProperty('--i', index + 1);
}
tabList.addEventListener('click', e => {
const tg = e.target;
//const parent = tg.parentNode;
if(tg.tagName === 'BUTTON') {
const index = [...tabList.children].indexOf(tg);
tabList.style.setProperty('--curr', index);
tabList.querySelector('[aria-selected="true"]').setAttribute('aria-selected', false);
//parent.querySelectorAll('[aria-selected="true"]').forEach(t => t.setAttribute("aria-selected", false));
tg.setAttribute('aria-selected', true);
grid.dataset.state = tg.dataset.setstate;
}
}, false);
tabList.addEventListener("keydown", e => {
// Move right
if (e.keyCode === 40 || e.keyCode === 38) {
e.preventDefault();
tabs[tabFocus].setAttribute("tabindex", -1);
if (e.keyCode === 40) {
tabFocus++;
// If we're at the end, go to the start
if (tabFocus >= tabs.length) {
tabFocus = 0;
}
// Move left
} else if (e.keyCode === 38) {
tabFocus--;
// If we're at the start, move to the end
if (tabFocus < 0) {
tabFocus = tabs.length - 1;
}
}
tabs[tabFocus].setAttribute("tabindex", 0);
tabs[tabFocus].focus();
}
});</script>

@include('site.login-register-modals')

@stop