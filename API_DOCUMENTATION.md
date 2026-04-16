# KisanStock Mobile API Documentation

## Base URL
```
https://kisanstock.com/api
```

## Authentication
The API uses Laravel Sanctum for authentication. After login, include the Bearer token in the Authorization header:
```
Authorization: Bearer {your_token_here}
```

---

## 📱 Authentication Endpoints

### 1. Phone Login
**POST** `/auth/phone-login`

Login with phone number and password.

**Request Body:**
```json
{
  "phone": "03001234567",
  "password": "your_password"
}
```

**Response:**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "phone": "03001234567",
    "email": "user@example.com"
  }
}
```

---

### 2. Phone Signup
**POST** `/auth/phone-signup`

Register new user with phone number.

**Request Body:**
```json
{
  "name": "John Doe",
  "phone": "03001234567",
  "password": "your_password",
  "password_confirmation": "your_password"
}
```

---

### 3. Mobile Register
**POST** `/auth/mobile-register`

Alternative registration endpoint for mobile.

---

### 4. Social Login
**POST** `/auth/loginsocial`

Login using social media credentials.

**Request Body:**
```json
{
  "provider": "facebook|google",
  "access_token": "provider_access_token"
}
```

---

### 5. WhatsApp Authentication
**POST** `/auth/whtasapp`

Authentication via WhatsApp.

---

## 🌾 Public Endpoints (No Authentication Required)

### Crops

#### Get All Crops
**GET** `/crop`

Returns list of all crops.

**Response:**
```json
[
  {
    "id": 1,
    "name": "Wheat",
    "name_ur": "گندم",
    "icon": "/images/crops/wheat.png",
    "color": "#FFD700",
    "sort": 1,
    "active": true
  }
]
```

---

#### Get Crop Types
**GET** `/crop/{crop_id}/type`

Get all types for a specific crop.

**Response:**
```json
[
  {
    "id": 1,
    "crop_id": 1,
    "name": "General",
    "name_ur": "عام"
  }
]
```

---

### Media Upload
**POST** `/media`

Upload media files (images, videos).

**Request:**
- Content-Type: `multipart/form-data`
- Field: `file`

**Response:**
```json
{
  "id": 1,
  "path": "/storage/media/image.jpg",
  "type": "image"
}
```

---

### Cities
**GET** `/cities`

Get all cities list.

**Response:**
```json
[
  {
    "id": 1,
    "name": "Lahore",
    "name_ur": "لاہور",
    "province": "Punjab"
  }
]
```

---

## 🔐 Protected Endpoints (Authentication Required)

### 🏠 Home & Popular

#### Get Crops
**GET** `/crops`

Get crops list for home screen.

---

#### Popular Deals
**POST** `/popular`

Get popular deals based on filters.

**Request Body:**
```json
{
  "city_id": 1,
  "crop_type_id": 1,
  "limit": 10
}
```

---

#### Latest Deals
**GET** `/latest`

Get latest deals.

---

#### Category Deals
**POST** `/catdeals`

Get deals by category.

**Request Body:**
```json
{
  "category_id": 1,
  "page": 1
}
```

---

#### Get Subcategories
**GET** `/subcats/{category_id}`

Get subcategories for a category.

---

## 📦 Deals Management

### Deals CRUD
**Resource Route:** `/deal`

- **GET** `/deal` - List all deals
- **POST** `/deal` - Create new deal
- **GET** `/deal/{id}` - Get single deal
- **PUT/PATCH** `/deal/{id}` - Update deal
- **DELETE** `/deal/{id}` - Delete deal

#### Create Deal Request:
```json
{
  "crop_type_id": 1,
  "qty": 100,
  "weight_id": 1,
  "demand": 5000,
  "note": "High quality wheat",
  "address": "Lahore, Punjab",
  "city_id": 1,
  "latitude": 31.5204,
  "longitude": 74.3587,
  "media_ids": [1, 2, 3]
}
```

---

### Home Deals
**GET** `/home-deals`

Get deals for home screen with pagination.

---

### Deals History
**POST** `/deals-history`

Get user's deal history.

**Request Body:**
```json
{
  "status": "active|sold|expired",
  "page": 1
}
```

---

### User Deals
**POST** `/user-deals`

Get current user's deals.

**Request Body:**
```json
{
  "status": "active|completed",
  "page": 1
}
```

---

### User Category Deals
**POST** `/user-cat-deals`

Get user's category-based deals.

---

## 💰 Bidding System

### Bids CRUD
**Resource Route:** `/bid`

- **GET** `/bid` - List all bids
- **POST** `/bid` - Place a bid
- **GET** `/bid/{id}` - Get single bid
- **PUT/PATCH** `/bid/{id}` - Update bid
- **DELETE** `/bid/{id}` - Delete bid

#### Create Bid Request:
```json
{
  "deal_id": 1,
  "bid_price": 5500,
  "note": "Interested in buying"
}
```

---

## 💬 Messaging & Chat

### Inbox Management
**Resource Route:** `/inbox`

- **GET** `/inbox` - List all conversations
- **POST** `/inbox` - Create new conversation
- **GET** `/inbox/{id}` - Get conversation
- **DELETE** `/inbox/{id}` - Delete conversation

---

### Get Inbox Chat
**GET** `/inbox-chat/{id}`

Get all messages in a conversation.

---

### Chat Types
**POST** `/chattypes`

Get chat types for deals.

**Request Body:**
```json
{
  "deal_id": 1
}
```

---

### Category Chat Types
**POST** `/cat-chattypes`

Get chat types for category deals.

---

### Chat Messages
**Resource Route:** `/chat`

- **GET** `/chat` - List messages
- **POST** `/chat` - Send message
- **GET** `/chat/{id}` - Get message
- **DELETE** `/chat/{id}` - Delete message

#### Send Message Request:
```json
{
  "inbox_id": 1,
  "message": "Hello, is this still available?",
  "media_id": 1
}
```

---

## 👤 User Profile

### Get Profile
**GET** `/user/profile`

Get current user's profile.

**Response:**
```json
{
  "id": 1,
  "name": "John Doe",
  "phone": "03001234567",
  "email": "user@example.com",
  "image": "/storage/profiles/user.jpg",
  "city_id": 1,
  "address": "Lahore, Punjab",
  "verified": true,
  "email_verified": false
}
```

---

### Update Profile
**POST** `/user/update`

Update user profile.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "newemail@example.com",
  "city_id": 1,
  "address": "New Address",
  "whatsapp": "03001234567",
  "image": "base64_image_or_media_id"
}
```

---

### Delete Account
**POST** `/user/delete`

Delete user account.

**Request Body:**
```json
{
  "password": "your_password",
  "reason": "Not using anymore"
}
```

---

### User Reviews
**POST** `/user/reviews`

Get reviews for a user.

**Request Body:**
```json
{
  "user_id": 1,
  "page": 1
}
```

---

### Email Verification

#### Send Verification Code
**POST** `/user/send-email-veri-code`

Send email verification code.

---

#### Verify Email Code
**POST** `/user/verify-email-veri-code`

Verify email with code.

**Request Body:**
```json
{
  "code": "123456"
}
```

---

### Phone Verification
**POST** `/user/verify-phone`

Verify phone number.

---

## 📊 Crop Rates

### Rates CRUD
**Resource Route:** `/rates`

- **GET** `/rates` - List all rates
- **POST** `/rates` - Create rate (for commission shops)
- **GET** `/rates/{id}` - Get single rate
- **PUT/PATCH** `/rates/{id}` - Update rate
- **DELETE** `/rates/{id}` - Delete rate

---

### Filter Rates
**POST** `/filter-rates`

Filter rates by crop, city, date.

**Request Body:**
```json
{
  "crop_type_id": 1,
  "city_id": 1,
  "date": "2026-01-14"
}
```

---

### City Rates
**Resource Route:** `/city-rates`

Get rates by city.

---

### City Rate History
**POST** `/city-rate-history`

Get historical rates for a city.

**Request Body:**
```json
{
  "city_id": 1,
  "crop_type_id": 1,
  "days": 30
}
```

---

### Rates Filter
**POST** `/rates-filter`

Advanced filtering for rates.

---

### Trending Rates Graph
**GET** `/trending-rates-graph`

Get trending crops data for graphs.

**Response:**
```json
{
  "crops": [
    {
      "crop_type_id": 1,
      "name": "Wheat",
      "rates": [
        {
          "date": "2026-01-14",
          "min_rate": 4800,
          "max_rate": 5200
        }
      ]
    }
  ]
}
```

---

## 🏪 Commission Shops

### Shops CRUD
**Resource Route:** `/commissionshop`

- **GET** `/commissionshop` - List all shops
- **POST** `/commissionshop` - Create shop
- **GET** `/commissionshop/{id}` - Get shop details
- **PUT/PATCH** `/commissionshop/{id}` - Update shop
- **DELETE** `/commissionshop/{id}` - Delete shop

#### Create Shop Request:
```json
{
  "name": "ABC Commission Shop",
  "about": "Dealing in all crops",
  "address": "Main Market, Lahore",
  "city_id": 1,
  "mobile": "03001234567",
  "whatsapp": "03001234567",
  "logo": "media_id",
  "banner": "media_id",
  "latitude": 31.5204,
  "longitude": 74.3587
}
```

---

### Favorite Shops
**Resource Route:** `/fav-shops`

- **GET** `/fav-shops` - List favorite shops
- **POST** `/fav-shops` - Add to favorites
- **DELETE** `/fav-shops/{id}` - Remove from favorites

---

### Report Shop
**Resource Route:** `/reported-shops`

Report inappropriate shops.

**Request Body:**
```json
{
  "shop_id": 1,
  "reason": "Fake information"
}
```

---

### Shop Ratings
**POST** `/rating`

Rate a shop.

**Request Body:**
```json
{
  "shop_id": 1,
  "rating": 5,
  "review": "Excellent service"
}
```

---

### Get Shop Ratings
**POST** `/get-ratings`

Get ratings for a shop.

**Request Body:**
```json
{
  "shop_id": 1,
  "page": 1
}
```

---

### Shop Crops
**GET** `/commissionshop-crops`

Get crops handled by commission shops.

---

### Update Shop Crops
**POST** `/commissionshop-crops`

Update crops for a shop.

**Request Body:**
```json
{
  "crop_type_ids": [1, 2, 3, 4]
}
```

---

### Post Crop Rates (Shop)
**POST** `/commissionshop-post-rates`

Post crop rates as a commission shop.

**Request Body:**
```json
{
  "crop_type_id": 1,
  "min_price": 4800,
  "max_price": 5200,
  "rate_date": "2026-01-14"
}
```

---

### Get Nearby Shops
**POST** `/commissionshop-get-nearby`

Get nearby commission shops.

**Request Body:**
```json
{
  "latitude": 31.5204,
  "longitude": 74.3587,
  "radius": 50
}
```

---

## 📝 Reviews & Reactions

### Reviews CRUD
**Resource Route:** `/review`

- **GET** `/review` - List reviews
- **POST** `/review` - Create review
- **GET** `/review/{id}` - Get review
- **PUT/PATCH** `/review/{id}` - Update review
- **DELETE** `/review/{id}` - Delete review

---

### Review History
**POST** `/review-history`

Get review history.

---

### Reactions (Like/Dislike)
**Resource Route:** `/reaction`

- **POST** `/reaction` - React to deal
- **DELETE** `/reaction/{id}` - Remove reaction

**Request Body:**
```json
{
  "deal_id": 1,
  "type": "like|dislike"
}
```

---

## 📍 Address Management

### Address CRUD
**Resource Route:** `/address`

- **GET** `/address` - List addresses
- **POST** `/address` - Create address
- **GET** `/address/{id}` - Get address
- **PUT/PATCH** `/address/{id}` - Update address
- **DELETE** `/address/{id}` - Delete address

---

## 🔔 Notifications

### Notifications CRUD
**Resource Route:** `/notification`

- **GET** `/notification` - List notifications
- **GET** `/notification/{id}` - Mark as read
- **DELETE** `/notification/{id}` - Delete notification

---

## 📱 Social Feed

### Feeds CRUD
**Resource Route:** `/feeds`

- **GET** `/feeds` - List all feeds
- **POST** `/feeds` - Create post
- **GET** `/feeds/{id}` - Get single post
- **PUT/PATCH** `/feeds/{id}` - Update post
- **DELETE** `/feeds/{id}` - Delete post

#### Create Feed Request:
```json
{
  "content": "Check out my new crop harvest!",
  "media_ids": [1, 2, 3],
  "crop_type_id": 1
}
```

---

### Feed Likes
**Resource Route:** `/feeds/{feed_id}/likes`

- **POST** `/feeds/{feed_id}/likes` - Like post
- **DELETE** `/feeds/{feed_id}/likes/{id}` - Unlike post

---

### Feed Comments
**Resource Route:** `/feeds/{feed_id}/comments`

- **GET** `/feeds/{feed_id}/comments` - Get comments
- **POST** `/feeds/{feed_id}/comments` - Add comment
- **DELETE** `/feeds/{feed_id}/comments/{id}` - Delete comment

---

## 🏭 Mills & Industries

### Feed Mill Rates
**GET** `/feed-mill-rates`

Get feed mill rates.

---

### Sugar Mill Rates
**GET** `/sugar-mill-rates`

Get sugar mill rates.

---

## 🎥 Videos

### Videos CRUD
**Resource Route:** `/videos`

- **GET** `/videos` - List videos
- **POST** `/videos` - Upload video
- **GET** `/videos/{id}` - Get video
- **DELETE** `/videos/{id}` - Delete video

---

## 💼 Categories

### Category Deals
**Resource Route:** `/categories-deal`

CRUD operations for category-based deals.

---

### Category Bids
**Resource Route:** `/categories-bid`

Bidding system for category deals.

---

### Category Reactions
**Resource Route:** `/categories-reaction`

Reactions for category deals.

---

### Category Inbox
**Resource Route:** `/categories-inbox`

Messaging for category deals.

---

## 💬 Support System

### Support Tickets
**Resource Route:** `/support`

- **GET** `/support` - List tickets
- **POST** `/support` - Create ticket
- **GET** `/support/{id}` - Get ticket
- **PUT/PATCH** `/support/{id}` - Update ticket

---

### Support Details
**Resource Route:** `/support/{ticket_id}/details`

- **GET** `/support/{ticket_id}/details` - Get messages
- **POST** `/support/{ticket_id}/details` - Add message

---

## ⚙️ Settings

### App Settings
**Resource Route:** `/settings`

- **GET** `/settings` - Get all settings
- **GET** `/settings/{key}` - Get specific setting

---

## 💳 Subscriptions & Payments

### Subscriptions
**Resource Route:** `/subscription`

- **GET** `/subscription` - List plans
- **POST** `/subscription` - Subscribe
- **GET** `/subscription/{id}` - Get subscription

---

### Payment Methods
**Resource Route:** `/payment-methods`

- **GET** `/payment-methods` - List payment methods
- **POST** `/payment-methods` - Add payment method

---

## 📢 Advertisements

### Ads Management
**Resource Route:** `/advertisement`

- **GET** `/advertisement` - List ads
- **GET** `/advertisement/{id}` - Get ad details

---

## 📖 Data Endpoints

### Create Deal Data
**GET** `/data/create-deal`

Get all necessary data for creating a deal (crops, types, weights, cities).

**Response:**
```json
{
  "crops": [...],
  "weights": [...],
  "cities": [...]
}
```

---

## 🔄 Response Format

### Success Response
```json
{
  "success": true,
  "data": {...},
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Validation error"]
  }
}
```

---

## 📝 Common Status Codes

- **200** - Success
- **201** - Created
- **400** - Bad Request
- **401** - Unauthorized
- **403** - Forbidden
- **404** - Not Found
- **422** - Validation Error
- **500** - Server Error

---

## 🔒 Security Notes

1. Always use HTTPS in production
2. Store tokens securely (encrypted storage)
3. Implement token refresh mechanism
4. Handle token expiration gracefully
5. Never log sensitive data
6. Implement rate limiting on client side

---

## 📱 Best Practices

1. **Caching**: Cache static data (crops, cities) locally
2. **Pagination**: Use pagination for large lists
3. **Image Optimization**: Compress images before upload
4. **Error Handling**: Implement proper error handling
5. **Offline Support**: Store critical data for offline access
6. **Network Retry**: Implement retry logic for failed requests

---

## 🆘 Support

For API issues or questions, contact the development team.

---

**Last Updated:** January 14, 2026
**Version:** 1.0
