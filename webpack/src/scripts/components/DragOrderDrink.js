import React from 'react';

var Hammer = require('react-hammerjs');

var HammerOptions = {
  touchAction:true,
  recognizers:{}
};


const DragOrderDrink = React.createClass({
  getInitialState: function() {
    return {
      state: '',
      position: 88,
      onPanMove: 0,
      onPanFinished: true,
    }
  },
  componentDidMount: function() {
      // console.log('componentDidMount');
  },

  handlePan: function(ev) {
    // 結束拖拉
    if (ev.isFinal === true) {
      $('body').removeClass("touchMode");
      if (Math.abs(ev.deltaX) > this.state.position / 2) {
        if (ev.deltaX > 0) {
          this.showEdit();
        }else {
          this.showDelete();
        }
      }else {
        this.reset();
      }
    }else {
      // 拖拉移動
      if (ev.isFinal === false) {
        $('body').addClass("touchMode");
        if (Math.abs(ev.deltaX) < this.state.position) {
          this.setState({
            onPanMove: ev.deltaX,
            onPanFinished: false,
          });
        }

      }
    }
  },
  showEdit: function(){
    this.setState({
      onPanMove: this.state.position,
      onPanFinished: true,
    });
  },
  showDelete: function(){
    this.setState({
      onPanMove: -this.state.position,
      onPanFinished: true,
    });
  },
  reset: function() {
    this.setState({
      onPanMove: 0,
      onPanFinished: true,
    });
  },
  render: function(){
    if (this.state.onPanFinished === true) {
      var left = this.state.onPanMove;
      var transition = "left 0.5s";
    }else {
      var left  = this.state.onPanMove;
      var transition = "inherit";
    }
    var outer_style = {
      left: left,
      transition: transition
    };

    return (
      <Hammer option={HammerOptions} onPan={this.handlePan}>
        <div style={outer_style} className="o-drink-item">
          <div className="edit-o-drink"><span className="glyphicon glyphicon-align-justify"></span></div>
          <div className="delete-o-drink"><span className="glyphicon glyphicon-remove" aria-hidden="true"></span></div>
          <div className="o-drink-detail">
            <div className="o-drink-title">四季菁茶</div>
            <div className="o-drink-info">大杯　半糖　少冰</div>
            <div className="o-drink-amount">1</div>
          </div>
        </div>
      </Hammer>
    )
  }
});




module.exports = DragOrderDrink;


