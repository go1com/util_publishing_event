<?php

namespace go1\util\publishing\event\pipeline;

use Doctrine\DBAL\Connection;
use go1\util\eck\EckHelper;
use go1\util\publishing\event\EventPipeline;

/**
 * Embed the user eck fields to the event payload
 */
class EckPipeline extends EventPipeline
{
    public function __construct(Connection $db = null, \stdClass $portal = null, int $userId = 0)
    {
        $embeds = [];
        if ($db && $portal && $userId) {
            $entity = EckHelper::load($db, $portal->title, 'account', $userId);
            $entity = json_decode(json_encode($entity));
            $embeds = $this->format($db, $portal->id, $entity);
        }

        parent::__construct('eck', $embeds);
    }

    private function format(Connection $db, $portalId, $entity)
    {
        $metadata = EckHelper::metadata($db, $entity->instance, $entity->entity_type);

        $fields = [];
        foreach ($entity as $fieldName => $values) {
            if (in_array($fieldName, ['instance', 'entity_type', 'id']) || !is_array($values)) {
                continue;
            }
            if ($field = $metadata->field($fieldName)) {
                foreach ($values as $value) {
                    $fields[$fieldName]['value_' . $field->type()][] = $field->format((array) $value, true)['value'];
                }
            }
        }

        return ['fields_' . $portalId => $fields];
    }
}
