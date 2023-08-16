<?php

function Main_menu(){

    echo '
<nav class="main-menu">
  <img class="logo" src="/icdr/common/images/mcilogo.png">  
  <div class="settings">iCDR</div>
  <div class="scrollbar" id="style-1">
  <ul>
    <li>
      <a href="http://startific.com">
        <i class="glyphicon glyphicon-home fa fa-lg"></i>
        <span class="nav-text">Home</span>
      </a>
    </li>      
    <li>                                 
      <a href="http://10.15.90.200/icdr/layout/dashboardcbsvoice.php">
        <i class="glyphicon glyphicon-th-large fa fa-lg"></i>
        <span class="nav-text">CBS Voice</span>
      </a>
    </li>   
    <li>                                 
      <a href="http://10.15.90.200/icdr/layout/dashboardcbsdata.php">
        <i class="glyphicon glyphicon-th-large fa fa-lg"></i>
        <span class="nav-text">CBS Data</span>
      </a>
    </li>
    <li>                                 
      <a href="http://10.15.90.200/icdr/layout/dashboardcbssms.php">
        <i class="glyphicon glyphicon-th-large fa fa-lg"></i>
        <span class="nav-text">CBS SMS</span>
      </a>
    </li>
    <li>                                 
      <a href="http://10.15.90.200/icdr/layout/dashboardcbsmsc.php">
        <i class="glyphicon glyphicon-th-large fa fa-lg"></i>
        <span class="nav-text">CBS MSC Success</span>
      </a>
    </li>   
    <li class="darkerlishadow">
      <a href="http://10.15.90.200/icdr/layout/cbsseemomttraffic.php">
        <i class="glyphicon glyphicon-earphone fa fa-lg"></i>
        <span class="nav-text">SEE MO/MT Traffic</span>
      </a>
    </li>
    <li class="darkerli">
      <a href="http://10.15.90.200/icdr/layout/cbsseemomterror.php">
        <i class="glyphicon glyphicon-earphone fa fa-lg"></i>
        <span class="nav-text">SEE MO/MT Error</span>
      </a>
    </li>
    <li class="darkerli">
      <a href="http://10.15.90.200/icdr/layout/cbsseemomtsuccessrate.php">
        <i class="glyphicon glyphicon-earphone fa fa-lg"></i>
        <span class="nav-text">SEE MO/MT SR</span>
      </a>
    </li>
    <li class="darkerli">
      <a href="http://10.15.90.200/icdr/layout/cbsseerecmsctraffic.php">
        <i class="glyphicon glyphicon-earphone fa fa-lg"></i>
        <span class="nav-text">CBS MSC Traffic</span>
      </a>
    </li>
    <li class="darkerli">
      <a href="http://10.15.90.200/icdr/layout/cbsseereccalltypetraffic.php">
        <i class="glyphicon glyphicon-earphone fa fa-lg"></i>
        <span class="nav-text">CBS Call Type Traffic</span>
      </a>
    </li>
    <li class="darkerli">
      <a href="http://10.15.90.200/icdr/layout/cbsseemgmtraffic.php">
        <i class="glyphicon glyphicon-edit fa fa-lg"></i>
        <span class="nav-text">SEE MGM Traffic</span>
      </a>
    </li>
    <li class="darkerli">
    <a href="http://10.15.90.200/icdr/layout/cbsseeivrcalltraffic.php">
      <i class="glyphicon glyphicon-bullhorn fa fa-lg"></i>
      <span class="nav-text">SEE IVR Call Traffic</span>
    </a>
  </li>
  <li class="darkerli">
    <a href="http://10.15.90.200/icdr/layout/cbscbpdatatraffic.php">
      <i class="glyphicon glyphicon-sort fa fa-lg"></i>
      <span class="nav-text">CBP Data Traffic</span>
    </a>
  </li>
  <li class="darkerli">
    <a href="http://10.15.90.200/icdr/layout/cbscbpdataerror.php">
      <i class="glyphicon glyphicon-sort fa fa-lg"></i>
      <span class="nav-text">CBP Data Error</span>
    </a>
  </li>
  <li class="darkerli">
    <a href="http://10.15.90.200/icdr/layout/cbscbpdatasuccessrate.php">
      <i class="glyphicon glyphicon-sort fa fa-lg"></i>
      <span class="nav-text">CBP Data SR</span>
    </a>
  </li>
  <li class="darkerli">
    <a href="http://10.15.90.200/icdr/layout/cbscbpsmstraffic.php">
      <i class="glyphicon glyphicon-envelope fa fa-lg"></i>
      <span class="nav-text">CBP SMS Traffic</span>
    </a>
  </li>
  <li class="darkerli">
    <a href="http://10.15.90.200/icdr/layout/cbscbpsmserror.php">
      <i class="glyphicon glyphicon-envelope fa fa-lg"></i>
      <span class="nav-text">CBP SMS Error</span>
    </a>
  </li>
  <li class="darkerli">
    <a href="http://10.15.90.200/icdr/layout/cbscbpsmssuccessrate.php">
      <i class="glyphicon glyphicon-envelope fa fa-lg"></i>
      <span class="nav-text">CBP SMS SR</span>
    </a>
  </li>
  <li class="darkerli">
    <a href="http://10.15.90.200/icdr/layout/cbscbpaanalysis.php">
      <i class="glyphicon glyphicon-retweet fa fa-lg"></i>
      <span class="nav-text">CBPA Analysis</span>
    </a>
  </li>
  </ul>
</nav>
';
};
