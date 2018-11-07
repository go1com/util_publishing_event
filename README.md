Publishing Event [![Build Status](https://travis-ci.org/go1com/util_publishing_event.svg?branch=master)](https://travis-ci.org/go1com/util_publishing_event)
====

- Provide the functionality to format the event message.
- Embed the data to the message depend on the provided pipelines

### Usage
```
$event = new UserEvent($payload, $routingKey, $context);
$event->pipelines($connection, $request);

$message = (new MQEventHandler)->process($event);
``` 
