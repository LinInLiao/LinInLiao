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
    var extras = this.props.drink.extras.map(function(extra){
      return (<span>{extra}</span>);
    });

    return (
      <Hammer option={HammerOptions} onPan={this.handlePan} onTap={this.reset}>
        <div style={outer_style} className="o-drink-item">
          <div className="edit-o-drink"><span className="glyphicon glyphicon-align-justify"></span></div>
          <div className="delete-o-drink"><span className="glyphicon glyphicon-remove" aria-hidden="true"></span></div>
          <div className="o-drink-detail">
            <div className="o-drink-title">{this.props.drink.drink_name} <span> ${this.props.drink.total_price}</span></div>
              <div className="o-drink-info">
                <span>{this.props.drink.drink_size}</span>，
                <span>{this.props.drink.drink_coldheat}</span>，
                <span>{this.props.drink.drink_coldheat_level}</span>，
                <span>{this.props.drink.drink_sugar}</span>
                <span>({extras})</span>
              </div>
            <div className="o-drink-amount">{this.props.drink.amount}</div>
          </div>
        </div>
      </Hammer>
    )
  }
});




module.exports = DragOrderDrink;


