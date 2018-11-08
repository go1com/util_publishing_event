Publishing Event [![Build Status](https://travis-ci.org/go1com/util_publishing_event.svg?branch=master)](https://travis-ci.org/go1com/util_publishing_event)
====

- Provide the functionality to format the event message.
- Embed the data to the message depend on the provided pipelines

## Usage
```
$event = new UserEvent($user, 'user.create');
$event->setDb($this->db);
$event->pipelines();

$message = (new MQEventHandler)->process($event);
``` 

## Custom

### Extends the event
- We can extend the existing event or the Event class
```
class AccountEvent extends UserEvent

OR

class NoteEvent extends Event
```

- Define the event pipelines
```
class NoteEvent extends Event

public function example()
{
  $pipelines = [];
  ............
  $this->setPipelines($pipelines);
}
```

### Extends the event pipeline
- We can extend the existing pipeline or implement the EventPipelineInterface
```
class PortalPipeline extends UserEvent

OR

class NotePipeline implements EventPipelineInterface
```
