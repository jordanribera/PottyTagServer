!function(){"use strict";angular.module("pottytag",["ngCookies","ngSanitize","ngResource","ngRoute","ngMaterial"])}(),function(){"use strict";function t(){function t(){return e}var e=[{title:"AngularJS",url:"https://angularjs.org/",description:"HTML enhanced for web apps!",logo:"angular.png"},{title:"BrowserSync",url:"http://browsersync.io/",description:"Time-saving synchronised browser testing.",logo:"browsersync.png"},{title:"GulpJS",url:"http://gulpjs.com/",description:"The streaming build system.",logo:"gulp.png"},{title:"Jasmine",url:"http://jasmine.github.io/",description:"Behavior-Driven JavaScript.",logo:"jasmine.png"},{title:"Karma",url:"http://karma-runner.github.io/",description:"Spectacular Test Runner for JavaScript.",logo:"karma.png"},{title:"Protractor",url:"https://github.com/angular/protractor",description:"End to end test framework for AngularJS applications built on top of WebDriverJS.",logo:"protractor.png"},{title:"Angular Material Design",url:"https://material.angularjs.org/#/",description:"The Angular reference implementation of the Google's Material Design specification.",logo:"angular-material.png"},{title:"Sass (Node)",url:"https://github.com/sass/node-sass",description:"Node.js binding to libsass, the C version of the popular stylesheet preprocessor, Sass.",logo:"node-sass.png"},{key:"jade",title:"Jade",url:"http://jade-lang.com/",description:"Jade is a high performance template engine heavily influenced by Haml and implemented with JavaScript for node.",logo:"jade.png"}];this.getTec=t}angular.module("pottytag").service("webDevTec",t)}(),function(){"use strict";function t(){function t(t){var e=this;e.relativeDate=t(e.creationDate).fromNow()}var e={restrict:"E",templateUrl:"app/components/navbar/navbar.html",scope:{creationDate:"="},controller:t,controllerAs:"vm",bindToController:!0};return t.$inject=["moment"],e}angular.module("pottytag").directive("acmeNavbar",t)}(),function(){"use strict";function t(t){function e(e,n,o,a){var r,i=t(n[0],{typeSpeed:40,deleteSpeed:40,pauseDelay:800,loop:!0,postfix:" "});n.addClass("acme-malarkey"),angular.forEach(e.extraValues,function(t){i.type(t).pause()["delete"]()}),r=e.$watch("vm.contributors",function(){angular.forEach(a.contributors,function(t){i.type(t.login).pause()["delete"]()})}),e.$on("$destroy",function(){r()})}function n(t,e){function n(){return o().then(function(){t.info("Activated Contributors View")})}function o(){return e.getContributors(10).then(function(t){return a.contributors=t,a.contributors})}var a=this;a.contributors=[],n()}var o={restrict:"E",scope:{extraValues:"="},template:"&nbsp;",link:e,controller:n,controllerAs:"vm"};return n.$inject=["$log","githubContributor"],o}angular.module("pottytag").directive("acmeMalarkey",t),t.$inject=["malarkey"]}(),function(){"use strict";function t(t,e){function n(n){function a(t){return t.data}function r(e){t.error("XHR Failed for getContributors.\n"+angular.toJson(e.data,!0))}return n||(n=30),e.get(o+"/contributors?per_page="+n).then(a)["catch"](r)}var o="https://api.github.com/repos/Swiip/generator-gulp-angular",a={apiHost:o,getContributors:n};return a}angular.module("pottytag").factory("githubContributor",t),t.$inject=["$log","$http"]}(),function(){"use strict";function t(t,e,n,o,a,r,i){function l(){var t="http://spiralpower.net/pottytag/?r=";a.get(t+"status").success(function(t){console.log("success: ",t),u.apiData=t,c(t),u.lastChecked=new Date,console.log("checked bathroom status: ",u.lastChecked)}).error(function(t){console.log("error: ",t)})}function c(t){u.maleNum=+t.m_population,u.femaleNum=+t.f_population,u.occupied=u.maleNum+u.femaleNum>0?!0:!1}function s(){u.relativeDate=i(u.lastChecked).fromNow()}var u=this;u.getAPIdata=l,u.apiData="Checking status...",u.occupied=!1,u.maleNum=0,u.femaleNum=0,u.relativeDate="...",l();var p=r(l,3e4),g=r(s,1e3);t.$on("destroy",function(){console.log("scope destroy event"),r.cancel(p),r.cancel(g)})}angular.module("pottytag").controller("MainController",t),t.$inject=["$scope","$timeout","webDevTec","toastr","$http","$interval","moment"]}(),function(){"use strict";function t(t){t.debug("runBlock end")}angular.module("pottytag").run(t),t.$inject=["$log"]}(),function(){"use strict";function t(t){t.when("/",{templateUrl:"app/main/main.html",controller:"MainController",controllerAs:"main"}).otherwise({redirectTo:"/"})}angular.module("pottytag").config(t),t.$inject=["$routeProvider"]}(),function(){"use strict";angular.module("pottytag").constant("malarkey",malarkey).constant("toastr",toastr).constant("moment",moment)}(),function(){"use strict";function t(t,e){t.debugEnabled(!0),e.options.timeOut=3e3,e.options.positionClass="toast-top-right",e.options.preventDuplicates=!0,e.options.progressBar=!0}angular.module("pottytag").config(t),t.$inject=["$logProvider","toastr"]}(),angular.module("pottytag").run(["$templateCache",function(t){t.put("app/main/main.html",'<div layout="vertical" layout-fill=""><md-content><section class="jumbotron"><h1>Potty Tag</h1></section><div class="status-display"><h2>Bathroom status: <span ng-class="main.occupied ? \'occupied\' : \'available\'">{{main.occupied ? \'Occupied\' : \'Available\'}}</span></h2><ul ng-if="main.occupied"><li ng-if="main.maleNum > 0">{{main.maleNum}} {{main.maleNum === 1 ? \'guy\' : \'guys\'}}</li><li ng-if="main.femaleNum > 0">{{main.femaleNum}} {{main.femaleNum === 1 ? \'lady\' : \'ladies\'}}</li></ul><p ng-if="!main.occupied">There is currently no one checked in to the bathroom.</p><p style="font-size:small">Last checked {{main.relativeDate}}</p><div class="check-status" ng-click="main.getAPIdata()">Check Status</div></div></md-content></div>'),t.put("app/components/navbar/navbar.html","")}]);