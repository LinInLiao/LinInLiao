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
      submit_path: '/order/add-drink',
      drink_data: {
        name: '',
        sizes: [],
        extras: [],
        coldheat_levels: [],
        sugars: [],
        store_coldheat_id: '',
      },
      selected: {
        size: '',
        amount: 1,
      },
      dialog: {
        title: '驗證失敗',
        message: '',
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
    var submitData = {};
    var form = e.target;
    submitData = {
      order_id: this.state.order_id,
      drink_id: this.state.drink_id,
      store_coldheat_id: this.state.drink_data.store_coldheat_id,
      drink_amount: this.state.selected.amount,
      drink_size: form.elements.drinkSizes.value,
      drink_sugar: form.elements.drinkSugar.value,
      drink_ice: form.elements.drinkIce.value,
      drink_extras: []
    }
    if (form.elements.drinkExtras !== undefined) {
      for (var i = 0, l = form.elements.drinkExtras.length; i < l; i++) {
        if (form.elements.drinkExtras[i].checked === true) {
          submitData.drink_extras.push(form.elements.drinkExtras[i].value);
        }
      };
    }
    if (true === this.handleSubmitVaildation(submitData)) {
      $.ajax({
        method: "POST",
        url: this.state.submit_path,
        data: submitData,
        dataType: 'json',
        cache: false,
        success: function(data, status) {
          if (data.status === 'success') {
            this.setState({
              dialog:{ title: '提示', message: '新增成功'},
            });
            $('#myModal').modal('show');
          }else {
            this.setState({
              dialog:{ title: '提示', message: '新增失敗'},
            });
            $('#myModal').modal('show');
          }
        }.bind(this),
        error: function(xhr, status, err) {
          console.error(this.props.url, status, err.toString());
        }.bind(this)
      });
    }
  },
  handleSubmitVaildation: function(submitData) {
    var message = '';
    if (submitData.drink_sugar === undefined || submitData.drink_sugar == '') {
      message = '請選擇飲料甜度';
    }
    if (submitData.drink_size === undefined || submitData.drink_size == '') {
      message = '請選擇飲料尺寸';
    }
    if (submitData.drink_ice === undefined || submitData.drink_ice == '') {
      message = '請選擇飲料溫度';
    }
    console.log(submitData.drink_size);
    if (message === '') {
      return true;
    }else {
      this.setState({
        dialog:{ title: '驗證失敗', message: message},
      });
      $('#myModal').modal('show');
      return false;
    }
  },
  render: function(){
    console.log('render');
    var progress_completed = { "width": "50%"};
    return (
      <div className="drink">

        <div className="modal fade" id="myModal" role="dialog">
          <div className="modal-dialog">
            <div className="modal-content">
              <div className="modal-header">
                <button type="button" className="close" data-dismiss="modal">&times;</button>
                <h4 className="modal-title">{ this.state.dialog.title }</h4>
              </div>
              <div className="modal-body">
                <p>{ this.state.dialog.message }</p>
              </div>
              <div className="modal-footer">
                <button type="button" className="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

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
                      selectName="drinkIce"
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
                      selectName="drinkSugar"
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
                <div key={item.store_extra_id} className="extra-box">
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
          <button className="lin-button button-blue" type="submit">送出訂單</button>
        </form>
      </div>
    );
  }
});

module.exports = Drink;
