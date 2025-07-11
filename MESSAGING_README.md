# MSDGC Messaging Feature

This feature allows authenticated users to send email and SMS messages to all users or specific groups based on user tags.

## Features

- **Email Messages**: Send emails to users who have opted in for email notifications
- **SMS Messages**: Send SMS messages via email-to-SMS gateways to users who have opted in for SMS notifications
- **Recipient Filtering**: Send to all users or filter by specific tags
- **Message History**: View recent messages sent through the system
- **Real-time Statistics**: See how many users will receive each message type

## SMS Gateway Support

The system supports the following SMS carriers:

- Verizon: `@vtext.com`
- AT&T: `@txt.att.net`
- T-Mobile: `@tmomail.net`
- Sprint: `@messaging.sprintpcs.com`
- Boost Mobile: `@sms.myboostmobile.com`
- Cricket: `@sms.cricketwireless.net`
- MetroPCS: `@mymetropcs.com`
- Virgin Mobile: `@vmobl.com`
- US Cellular: `@email.uscc.net`
- Straight Talk: `@vtext.com`

## User Fields Required

For the messaging system to work properly, users need the following fields in their profile:

- `email` - User's email address
- `email_opt_in` - Boolean indicating if user wants to receive emails
- `sms` - User's phone number
- `provider` - User's SMS carrier (from the list above)
- `sms_opt_in` - Boolean indicating if user wants to receive SMS messages
- `tags` - Array of tags for filtering recipients

## How to Use

1. Navigate to the "Send Messages" page from the dashboard or navigation menu
2. Enter a subject line (used for emails only)
3. Enter your message content
4. Choose recipients:
   - **All Users**: Send to everyone who has opted in
   - **Users with Specific Tags**: Filter by selected tags
5. Review the recipient statistics to see how many users will receive the message
6. Click "Send Messages" to deliver

## Message Delivery Logic

- **Email**: Sent to users where `email_opt_in = true` and `email` is not null
- **SMS**: Sent to users where `sms_opt_in = true`, `sms` is not null, and `provider` is not null
- Users can receive both email and SMS if they've opted in for both
- Phone numbers are automatically cleaned (non-numeric characters removed) before sending
- Messages are sent to `{cleanedPhoneNumber}@{carrierGateway}.com`

## Security

- Only authenticated users can access the messaging feature
- All messages are logged with sender information, timestamp, and delivery statistics
- Failed message attempts are logged for debugging

## Database Tables

- `messages` - Stores message history and delivery statistics
- `users` - Contains user preferences and contact information

## Routes

- `GET /messages` - Display the messaging form
- `POST /messages/send` - Send messages to users
