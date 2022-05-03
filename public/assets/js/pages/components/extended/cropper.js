/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "../src/assets/js/pages/components/extended/cropper.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../src/assets/js/pages/components/extended/cropper.js":
/*!*************************************************************!*\
  !*** ../src/assets/js/pages/components/extended/cropper.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\r\n\r\n// Class definition\r\nvar KTCropperDemo = function() {\r\n\r\n  // Private functions\r\n  var initCropperDemo = function() {\r\n    var image = document.getElementById('image');\r\n\r\n    var options = {\r\n      crop: function(event) {\r\n        document.getElementById('dataX').value = Math.round(event.detail.x);\r\n        document.getElementById('dataY').value = Math.round(event.detail.y);\r\n        document.getElementById('dataWidth').value = Math.round(event.detail.width);\r\n        document.getElementById('dataHeight').value = Math.round(event.detail.height);\r\n        document.getElementById('dataRotate').value = event.detail.rotate;\r\n        document.getElementById('dataScaleX').value = event.detail.scaleX;\r\n        document.getElementById('dataScaleY').value = event.detail.scaleY;\r\n\r\n        var lg = document.getElementById('cropper-preview-lg');\r\n        lg.innerHTML = '';\r\n        lg.appendChild(cropper.getCroppedCanvas({width: 256, height: 160}));\r\n\r\n        var md = document.getElementById('cropper-preview-md');\r\n        md.innerHTML = '';\r\n        md.appendChild(cropper.getCroppedCanvas({width: 128, height: 80}));\r\n\r\n        var sm = document.getElementById('cropper-preview-sm');\r\n        sm.innerHTML = '';\r\n        sm.appendChild(cropper.getCroppedCanvas({width: 64, height: 40}));\r\n\r\n        var xs = document.getElementById('cropper-preview-xs');\r\n        xs.innerHTML = '';\r\n        xs.appendChild(cropper.getCroppedCanvas({width: 32, height: 20}));\r\n      },\r\n    };\r\n\r\n    var cropper = new Cropper(image, options);\r\n\r\n    var buttons = document.getElementById('cropper-buttons');\r\n    var methods = buttons.querySelectorAll('[data-method]');\r\n    methods.forEach(function(button) {\r\n      button.addEventListener('click', function(e) {\r\n        var method = button.getAttribute('data-method');\r\n        var option = button.getAttribute('data-option');\r\n        var option2 = button.getAttribute('data-second-option');\r\n\r\n        try {\r\n          option = JSON.parse(option);\r\n        }\r\n        catch (e) {\r\n        }\r\n\r\n        var result;\r\n        if (!option2) {\r\n          result = cropper[method](option, option2);\r\n        }\r\n        else if (option) {\r\n          result = cropper[method](option);\r\n        }\r\n        else {\r\n          result = cropper[method]();\r\n        }\r\n\r\n        if (method === 'getCroppedCanvas') {\r\n          var modal = document.getElementById('getCroppedCanvasModal');\r\n          var modalBody = modal.querySelector('.modal-body');\r\n          modalBody.innerHTML = '';\r\n          modalBody.appendChild(result);\r\n        }\r\n\r\n        var input = document.querySelector('#putData');\r\n        try {\r\n          input.value = JSON.stringify(result);\r\n        }\r\n        catch (e) {\r\n          if (!result) {\r\n            input.value = result;\r\n          }\r\n        }\r\n      });\r\n    });\r\n\r\n    // set aspect ratio option buttons\r\n    var radioOptions = document.getElementById('setAspectRatio').querySelectorAll('[name=\"aspectRatio\"]');\r\n    radioOptions.forEach(function(button) {\r\n      button.addEventListener('click', function(e) {\r\n        cropper.setAspectRatio(e.target.value);\r\n      });\r\n    });\r\n\r\n    // set view mode\r\n    var viewModeOptions = document.getElementById('viewMode').querySelectorAll('[name=\"viewMode\"]');\r\n    viewModeOptions.forEach(function(button) {\r\n      button.addEventListener('click', function(e) {\r\n        cropper.destroy();\r\n        cropper = new Cropper(image, Object.assign({}, options, {viewMode: e.target.value}));\r\n      });\r\n    });\r\n\r\n    var toggleoptions = document.getElementById('toggleOptionButtons').querySelectorAll('[type=\"checkbox\"]');\r\n    toggleoptions.forEach(function(checkbox) {\r\n      checkbox.addEventListener('click', function(e) {\r\n        var appendOption = {};\r\n        appendOption[e.target.getAttribute('name')] = e.target.checked;\r\n        options = Object.assign({}, options, appendOption);\r\n        cropper.destroy();\r\n        cropper = new Cropper(image, options);\r\n      })\r\n    })\r\n\r\n  };\r\n\r\n  return {\r\n    // public functions\r\n    init: function() {\r\n      initCropperDemo();\r\n    },\r\n  };\r\n}();\r\n\r\nKTUtil.ready(function() {\r\n  KTCropperDemo.init();\r\n});\r\n\n\n//# sourceURL=webpack:///../src/assets/js/pages/components/extended/cropper.js?");

/***/ })

/******/ });