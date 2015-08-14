(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({"/Users/nextdude/Dropbox/hmd/howmanydead.org/app/App.js":[function(require,module,exports){
'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

var _get = function get(_x, _x2, _x3) { var _again = true; _function: while (_again) { var object = _x, property = _x2, receiver = _x3; desc = parent = getter = undefined; _again = false; if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { _x = parent; _x2 = property; _x3 = receiver; _again = true; continue _function; } } else if ('value' in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

function _inherits(subClass, superClass) { if (typeof superClass !== 'function' && superClass !== null) { throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var React = require('react');

/*
var App = React.createClass({

  render: function() {
    return (
      <h1>Hello world!</h1>
    );
  }

});
*/

var App = (function (_React$Component) {
  _inherits(App, _React$Component);

  function App() {
    _classCallCheck(this, App);

    _get(Object.getPrototypeOf(App.prototype), 'constructor', this).apply(this, arguments);
  }

  _createClass(App, [{
    key: 'render',
    value: function render() {
      return React.createElement(
        'h1',
        null,
        'Hello world!'
      );
    }
  }]);

  return App;
})(React.Component);

module.exports = App;

},{"react":"react"}],"/Users/nextdude/Dropbox/hmd/howmanydead.org/specs/App-spec.js":[function(require,module,exports){
'use strict';

var App = require('./../app/App.js');
var React = require('react/addons');
var TestUtils = React.addons.TestUtils;

describe("App", function () {

  it("should render text: Hello world!", function () {
    var app = TestUtils.renderIntoDocument(React.createElement(App));
    expect(React.findDOMNode(app).textContent).toEqual('Hello world!');
  });
});

},{"./../app/App.js":"/Users/nextdude/Dropbox/hmd/howmanydead.org/app/App.js","react/addons":"react/addons"}]},{},["/Users/nextdude/Dropbox/hmd/howmanydead.org/specs/App-spec.js"])
//# sourceMappingURL=data:application/json;charset:utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyaWZ5L25vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCIvVXNlcnMvbmV4dGR1ZGUvRHJvcGJveC9obWQvaG93bWFueWRlYWQub3JnL2FwcC9BcHAuanMiLCIvVXNlcnMvbmV4dGR1ZGUvRHJvcGJveC9obWQvaG93bWFueWRlYWQub3JnL3NwZWNzL0FwcC1zcGVjLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBOzs7Ozs7Ozs7OztBQ0FBLElBQUksS0FBSyxHQUFHLE9BQU8sQ0FBQyxPQUFPLENBQUMsQ0FBQzs7Ozs7Ozs7Ozs7Ozs7SUFjdkIsR0FBRztZQUFILEdBQUc7O1dBQUgsR0FBRzswQkFBSCxHQUFHOzsrQkFBSCxHQUFHOzs7ZUFBSCxHQUFHOztXQUNELGtCQUFHO0FBQ1AsYUFDRTs7OztPQUFxQixDQUNyQjtLQUNIOzs7U0FMRyxHQUFHO0dBQVMsS0FBSyxDQUFDLFNBQVM7O0FBUWpDLE1BQU0sQ0FBQyxPQUFPLEdBQUcsR0FBRyxDQUFDOzs7OztBQ3RCckIsSUFBSSxHQUFHLEdBQUcsT0FBTyxDQUFDLGlCQUFpQixDQUFDLENBQUM7QUFDckMsSUFBSSxLQUFLLEdBQUcsT0FBTyxDQUFDLGNBQWMsQ0FBQyxDQUFDO0FBQ3BDLElBQUksU0FBUyxHQUFHLEtBQUssQ0FBQyxNQUFNLENBQUMsU0FBUyxDQUFDOztBQUV2QyxRQUFRLENBQUMsS0FBSyxFQUFFLFlBQVc7O0FBRXpCLElBQUUsQ0FBQyxrQ0FBa0MsRUFBRSxZQUFXO0FBQ2hELFFBQUksR0FBRyxHQUFHLFNBQVMsQ0FBQyxrQkFBa0IsQ0FBQyxLQUFLLENBQUMsYUFBYSxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUM7QUFDakUsVUFBTSxDQUFDLEtBQUssQ0FBQyxXQUFXLENBQUMsR0FBRyxDQUFDLENBQUMsV0FBVyxDQUFDLENBQUMsT0FBTyxDQUFDLGNBQWMsQ0FBQyxDQUFDO0dBQ3BFLENBQUMsQ0FBQztDQUVKLENBQUMsQ0FBQyIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uIGUodCxuLHIpe2Z1bmN0aW9uIHMobyx1KXtpZighbltvXSl7aWYoIXRbb10pe3ZhciBhPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7aWYoIXUmJmEpcmV0dXJuIGEobywhMCk7aWYoaSlyZXR1cm4gaShvLCEwKTt2YXIgZj1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK28rXCInXCIpO3Rocm93IGYuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixmfXZhciBsPW5bb109e2V4cG9ydHM6e319O3Rbb11bMF0uY2FsbChsLmV4cG9ydHMsZnVuY3Rpb24oZSl7dmFyIG49dFtvXVsxXVtlXTtyZXR1cm4gcyhuP246ZSl9LGwsbC5leHBvcnRzLGUsdCxuLHIpfXJldHVybiBuW29dLmV4cG9ydHN9dmFyIGk9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtmb3IodmFyIG89MDtvPHIubGVuZ3RoO28rKylzKHJbb10pO3JldHVybiBzfSkiLCJ2YXIgUmVhY3QgPSByZXF1aXJlKCdyZWFjdCcpO1xuXG4vKlxudmFyIEFwcCA9IFJlYWN0LmNyZWF0ZUNsYXNzKHtcblxuICByZW5kZXI6IGZ1bmN0aW9uKCkge1xuICAgIHJldHVybiAoXG4gICAgICA8aDE+SGVsbG8gd29ybGQhPC9oMT5cbiAgICApO1xuICB9XG5cbn0pO1xuKi9cblxuY2xhc3MgQXBwIGV4dGVuZHMgUmVhY3QuQ29tcG9uZW50IHtcbiAgcmVuZGVyKCkge1xuICAgIHJldHVybiAoXG4gICAgICA8aDE+SGVsbG8gd29ybGQhPC9oMT5cbiAgICApO1xuICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQXBwO1xuIiwidmFyIEFwcCA9IHJlcXVpcmUoJy4vLi4vYXBwL0FwcC5qcycpO1xudmFyIFJlYWN0ID0gcmVxdWlyZSgncmVhY3QvYWRkb25zJyk7XG52YXIgVGVzdFV0aWxzID0gUmVhY3QuYWRkb25zLlRlc3RVdGlscztcblxuZGVzY3JpYmUoXCJBcHBcIiwgZnVuY3Rpb24oKSB7XG5cbiAgaXQoXCJzaG91bGQgcmVuZGVyIHRleHQ6IEhlbGxvIHdvcmxkIVwiLCBmdW5jdGlvbigpIHtcbiAgICB2YXIgYXBwID0gVGVzdFV0aWxzLnJlbmRlckludG9Eb2N1bWVudChSZWFjdC5jcmVhdGVFbGVtZW50KEFwcCkpO1xuICAgIGV4cGVjdChSZWFjdC5maW5kRE9NTm9kZShhcHApLnRleHRDb250ZW50KS50b0VxdWFsKCdIZWxsbyB3b3JsZCEnKTtcbiAgfSk7XG5cbn0pO1xuXG4iXX0=
