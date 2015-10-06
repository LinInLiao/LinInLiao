import React from 'react';
import _ from 'underscore';
var DragSelect = require('./DragSelect.js');

require('react/addons');


var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;

var Hammer = require('react-hammerjs');

var HammerOptions = {
  touchAction:true,
  recognizers:{
    tap:{
      time:600, threshold:100
    },
    pan:{
      event: Hammer.panend
    }
  }
};


const RadioSizeBox = React.createClass({
  propTypes: {
    itemName: React.PropTypes.string,
    itemKey: React.PropTypes.string,
    itemPrice: React.PropTypes.string,
    itemSelected: React.PropTypes.string,
  },
  render: function(){
    var radioClass = 'radio-button'
    if (this.props.itemSelected === this.props.itemKey) {
       radioClass = radioClass + ' ' + 'actived';
    }
    return (
      <div className={radioClass} value={this.props.itemKey}>
        <input type="radio" name="drinkSizes" value={this.props.itemKey}/>
        <div className="radio-text">
          <span className="radio-name">{this.props.itemName}</span><br/>
          <span className="radio-price">${this.props.itemPrice}</span>
        </div>
      </div>
    );
  }
});



const Drink = React.createClass({
  getInitialState: function() {
    var path = window.location.href.split("/").reverse();
    var coldheat_id = path[0];
    var drink_id = path[1];
    var order_id = path[2];

    return {
      order_id: order_id,
      drink_id: drink_id,
      coldheat_id: coldheat_id,
      soruce_path: '/resource/oDrink/' + drink_id + '/' + coldheat_id,
      drink_data: {
        name: '',
        sizes: [],
        extras: [],
        coldheat_levels: [],
        sugars: [],
      },
      selected: {
        size: '',
        amount: 1,
      }
    }
  },
  componentDidMount: function() {
    this.loadDrink();
  },
  loadDrink: function() {
    $.ajax({
      url: this.state.soruce_path,
      dataType: 'json',
      cache: false,
      success: function(data, status) {
        if (data.drink != undefined) {
          var drink = {
            name: data.drink.name
          };
          this.setState({
            drink_data: data.drink,
          });
        }
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  handleAmount: function(action){
    console.log('handleAmount');
    var selected = this.state.selected;
    var amount = this.state.selected.amount;
    if (action === 'plus') {
      selected.amount++;
    }
    if (action === 'minus' && selected.amount > 1) {
      selected.amount--;
    }
    if (selected.amount > 0) {
      this.setState({
        selected:　selected,
      });
    }

  },
  handleSizeSelected: function(event) {
    console.log('handleSizeSelected');
    var selected = this.state.selected;
    if (selected.size !== event.target.value) {
      selected.size = event.target.value;
      this.setState({
        selected: selected,
      });
    }
  },
  handleSubmitOrder: function(e) {
    e.preventDefault();
    var form = e.target;
    var drink_size = form.elements.drinkSizes.value;
    console.log(form.elements.drinkExtras);

  },
  render: function(){
    console.log('render');
    var progress_completed = { "width": "50%"};
    return (
      <div className="drink">
        <div className="drink-name">{this.state.drink_data.name}</div>
        <form onSubmit={this.handleSubmitOrder}>
          <div className="drink-progress">
            <div className="shark-bg"></div>
            <div style={progress_completed} className="shark-completed"></div>
          </div>
          <div className="choice-amount">
            <Hammer option={HammerOptions} onTap={this.handleAmount.bind(this, 'minus')}><div className="minus glyphicon glyphicon-minus"></div></Hammer>
            <div className="amount-count">{this.state.selected.amount}</div>
            <Hammer option={HammerOptions} onTap={this.handleAmount.bind(this, 'plus')}><div className="plus glyphicon glyphicon-plus"></div></Hammer>
          </div>
          <hr/>
          <div className="choice-size" onChange={this.handleSizeSelected}>
            {this.state.drink_data.sizes.map(function(item){
              return (<RadioSizeBox key={item.id} itemSelected={this.state.selected.size} itemName={item.name} itemKey={item.id} itemPrice={item.price}/>)
            }.bind(this))}
          </div>
          <hr/>
          <div className="choice-ice">
            <DragSelect>
              {this.state.drink_data.coldheat_levels.map(function(item){
                 return (
                    <DragSelect.SelectItem
                      key={item.store_coldheat_level_id}
                      title={item.name}
                      selectKey={item.store_coldheat_level_id}
                      ref={item.store_coldheat_level_id}/>
                  )
              })}
              </DragSelect>
          </div>
          <hr/>
          <div className="choice-sugar">
            <DragSelect>
              {this.state.drink_data.sugars.map(function(item){
                 return (
                    <DragSelect.SelectItem
                      key={item.store_sugar_id}
                      title={item.name}
                      selectKey={item.store_sugar_id}
                      ref={item.store_sugar_id}/>
                  )
              })}
              </DragSelect>
          </div>

          <hr/>
          <div className="choice-extra">
            {this.state.drink_data.extras.map(function(item){
              return (
                <div className="extra-box">
                  <input type="checkbox" name="drinkExtras" id={item.store_extra_id} value={item.store_extra_id}/>
                  <label htmlFor={item.store_extra_id}>
                    <div className="extra-text">
                      <div className="extra-name">{item.name}</div>
                      <div className="extra-price">{'$' + item.price}</div>
                    </div>
                  </label>
                </div>)
            }.bind(this))}
          </div>
          <button type="submit">送出訂單</button>
        </form>
      </div>
    );
  }
});

module.exports = Drink;
