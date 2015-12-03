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
      onPanFinishedPostion: 0,
      active_item: '',
    }
  },
  componentDidMount: function() {
  },
  componentWillReceiveProps: function(nextProps) {
      if (nextProps.active !== this.props.itemKey) {
        this.reset();
      }
  },
  handlePan: function(ev) {
    // 結束拖拉
    if (ev.isFinal === true) {
      $('body').removeClass("touchMode");
      if (Math.abs(ev.deltaX + this.state.onPanFinishedPostion) > this.state.position / 2) {
        if (ev.deltaX > 0) {
          this.showEdit();
        }else {
          this.showDelete();
        }
      }else {
        this.reset();
      }
    }else {
      $('body').addClass("touchMode");
      if (Math.abs(ev.deltaX + this.state.onPanFinishedPostion) > this.state.position) {
        if (ev.deltaX > 0) {
          this.setState({
            onPanMove: 0,
            onPanFinishedPostion: this.state.position,
            onPanFinished: false,
          });
        }else {
          this.setState({
            onPanMove: 0,
            onPanFinishedPostion: -this.state.position,
            onPanFinished: false,
          });
        }
      }else {
        this.setState({
          onPanMove: ev.deltaX,
          onPanFinished: false,
        });
      }
    }
  },
  showEdit: function(){
    this.setState({
      onPanFinishedPostion: this.state.position,
      onPanFinished: true,
    });
  },
  showDelete: function(){
    this.setState({
      onPanFinishedPostion: -this.state.position,
      onPanFinished: true,
    });
  },
  reset: function() {
    this.setState({
      onPanMove: 0,
      onPanFinishedPostion: 0,
      onPanFinished: true,
    });
  },
  render: function(){
    if (this.state.onPanFinished === true) {
      var transform = this.state.onPanFinishedPostion;
      var transition = "transform 0.3s";
    }else {
      var transform  = this.state.onPanFinishedPostion + this.state.onPanMove;
      var transition = "inherit";
    }
    var outer_style = {
      transform: "translate(" +  transform  + "px ,0px)",
      transition: transition,
      touchAction: "none",
      position: "position",
      top: "0",
      left: "0"
    };

    return (
      <Hammer option={HammerOptions} onPan={this.handlePan} onTap={this.reset}>
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


