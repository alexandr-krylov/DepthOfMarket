# Depth Of Market service

## Run on docker

    cd root directory
    docker-compose up -d
    dockeer exec -it depthofmarket_app_1 composer install
    docker exec -it depthofmarket_app_1 ./yii migrate
    yes

## DOM API
### 1. New order
POST /api/0/order

market
```
{
    "owner_id": "1", //TODO Autorization
    "ticker": "SM0825",
    "side": "buy",
    "type": "market",
    "quantity": "150"
}
```
limit
```
{
    "ticker": "SM0825",
    "side": "sell",
    "type": "limit",
    "quantity": "50",
    "price": 10001
}
```
### Get order
GET /api/0/order/{id}

### 2. Get my orders
GET /api/0/myorder/{my_id}

### Get DOM
GET /api/0/dom/{ticker}

    red orders
    green orders
    
* contract_id: CONTRACT
* side: sell|buy /* only one sell for one contract */
* type: market|limit
* quantity: 100
* price: 1000 {not required if type = market}

2. Statuses of order
* Active: created but not sold or bought
* Filled: fully bought or sold
* PartialFilled: partial bought or sold
* Canseled: deleted by owner
* Refused: not created due to auction conditions (not enough funds, e.t.c.)

3. Conditions for stopping auction
* time is over
* contract fully bought
* offered a price above the limit

Продавец выставляет максимальный дисконт

Спецификация лота множество про
