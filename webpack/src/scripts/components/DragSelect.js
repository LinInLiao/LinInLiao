import React from 'react';

var Hammer = require('react-hammerjs');

var HammerOptions = {
  touchAction:true,
  recognizers:{}
};


const DragSelect = React.createClass({
    propTypes: {
      children: React.PropTypes.oneOfType([
        React.PropTypes.array,
        React.PropTypes.element,
      ]).isRequired
    },

  getInitialState: function() {
    return {
      itemIndex: 1,
      itemWidth: 60,
      itemCounts: 0,
      onPanFinished: true,
      onPanMove: 0,
      showLimit: 3,
    }
  },
  componentDidMount: function() {
      // console.log('componentDidMount');
  },
  componentWillReceiveProps: function(newProps){
    if (newProps.children.length != this.state.itemCounts) {
      this.setState({
        itemCounts: newProps.children.length,
      });
    }
  },
  renderSelectItems: function(select_items) {
    var set_select_items = select_items.map(this.setCheckedItem);
    return set_select_items;
  },
  setCheckedItem: function(element) {
      var isChecked = false;
      var itemStyle = { "width": this.state.itemWidth + "px" }
      if (element.ref === this.props.children[this.state.itemIndex].key) {
        isChecked = true;
        itemStyle = {
          "width": this.state.itemWidth + "px",
          "fontSize": "1.5rem",
          "transition": "all 0.1s",
          "color": "#76C4DF",
          "fontFamily": "STHeitiTC-Light",
          "fontWeight": "bold"
        }
      }
      return React.cloneElement(element, {isChecked: isChecked , itemStyle: itemStyle});
  },
  next: function(){
    var new_index = this.state.itemIndex;
    if (this.state.itemIndex < this.state.itemCounts - 1) {
      var new_index =  this.state.itemIndex + 1;
    }
    this.setState({
      itemIndex: new_index,
      onPanFinished: true,
      onPanMove: 0,
    });
  },
  prev: function(){
    var new_index = this.state.itemIndex;
    if (this.state.itemIndex > 0) {
      var new_index =  this.state.itemIndex - 1;
    }
    this.setState({
      itemIndex: new_index,
      onPanFinished: true,
      onPanMove: 0,
    });
  },
  reset: function(){
    this.setState({
      itemIndex: this.state.itemIndex,
      onPanFinished: true,
      onPanMove: 0,
    });
  },
  handlePan: function(ev) {
    // 結束拖拉
    if (ev.isFinal === true) {
      if (Math.abs(ev.deltaX) > this.state.itemWidth / 2) {
        if (ev.deltaX > 0) {
          this.prev();
        }else {
          this.next();
        }
      }else {
        this.reset();
      }
    }else {
      // 拖拉移動
      if (ev.isFinal === false) {
        this.setState({
          onPanMove: ev.deltaX,
          onPanFinished: false,
        });
      }
    }
  },
  render: function(){
    if (!Array.isArray(this.props.children)) {
      this.props.children = [this.props.children];
    }
    var renderDragSelect = this.renderSelectItems(this.props.children);

    if (this.state.onPanFinished === true) {
      var left = (this.state.itemIndex - 1 ) * - this.state.itemWidth;
      var transition = "left 0.5s";
    }else {
      var left  = (this.state.itemIndex - 1 ) * - this.state.itemWidth + (this.state.onPanMove / 3);
      var transition = "inherit";
    }
    var wrapper_style = {
      width: this.state.itemWidth *  this.props.children.length,
      left: left,
      transition: transition
    };
    var outer_style = {
      width: this.state.itemWidth * this.state.showLimit,
    };

    return (
      <div className="drag-select">
        <Hammer option={HammerOptions} onPan={this.handlePan}>
          <div className="hammer-wrapper"></div>
        </Hammer>
        <div style={outer_style} className="select-wrapper-outer">
          <div style={wrapper_style} className="select-wrapper">
            {renderDragSelect}
          </div>
        </div>
      </div>
    )
  }
});

  DragSelect.SelectItem = React.createClass({
    propTypes: {
      title: React.PropTypes.string.isRequired,
      selectName: React.PropTypes.string.isRequired,
      selectKey: React.PropTypes.string.isRequired,
      itemStyle: React.PropTypes.object
    },
    getInitialState: function() {
      return {
        isChecked: false,
      }
    },
    handleChange: function() {},
    render:function () {
      var styles = this.props.itemStyle ? this.props.itemStyle : {};
      return (
        <div style={styles} className="select-item">
          <input type="radio"
          name={this.props.selectName}
          value={this.props.selectKey}
          onChange={this.handleChange} ref={this.props.ref}
          checked={this.props.isChecked}/>
          <div className="item-title"><span>{this.props.title}</span></div>
        </div>);
    }
  });



module.exports = DragSelect;


