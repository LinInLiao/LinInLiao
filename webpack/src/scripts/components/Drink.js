import React from 'react';
import _ from 'underscore';


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
      },
      selected: {
        size: '',
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
  handleSizeSelected: function(event) {
    if (this.state.selected.size !== event.target.value) {
      this.setState({
        selected:　{size: event.target.value},
      });
    }

  },
  render: function(){
    var progress_completed = { "width": "50%"};
    return (
      <div className="drink">
        <form>
          <div className="drink-name">{this.state.drink_data.name}</div>
          <div className="drink-progress">
            <div className="shark-bg"></div>
            <div style={progress_completed} className="shark-completed"></div>
          </div>
          <div onChange={this.handleSizeSelected} className="choice-size">
            {this.state.drink_data.sizes.map(function(item){
                return (<RadioSizeBox key={item.id} itemSelected={this.state.selected.size} itemName={item.name} itemKey={item.id} itemPrice={item.price} />)
              }.bind(this))}
          </div>

          <button>送出訂單</button>
        </form>
      </div>
    );
  }
});

module.exports = Drink;
