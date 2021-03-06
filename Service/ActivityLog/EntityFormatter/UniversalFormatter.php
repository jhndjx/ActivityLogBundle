<?php

namespace ActivityLogBundle\Service\ActivityLog\EntityFormatter;

use ActivityLogBundle\Entity\LogEntry;
use ActivityLogBundle\Entity\LogEntryInterface;

/**
 * Class UniversalFormatter
 * @package ActivityLogBundle\Service\ActivityLog\EntityFormatter
 */
class UniversalFormatter extends AbstractFormatter implements FormatterInterface
{
    /**
     * @param LogEntryInterface|LogEntry $log
     * @return array
     */
    public function format(LogEntryInterface $log)
    {
        $result = $log->toArray();

        $name = substr(strrchr(rtrim($log->getObjectClass(), '\\'), '\\'), 1);
        if ($log->isCreate()) {
            $result['message'] = sprintf('The entity <b>%s (%s)</b> was created.', $log->getName(), $name);
        } else if ($log->isRemove()) {
            $result['message'] = sprintf('The entity <b>%s (%s)</b> was removed.', $log->getName(), $name);
        } else if ($log->isUpdate()) {
            $result['message'] = sprintf(
                'The entity <b>%s (%s)</b> was updated.<br><b>Prev. data:</b> %s<br><b>New data:</b> %s',
                $log->getName(),
                $name,
                $this->toComment($log->getOldData()),
                $this->toComment($log->getData())
            );
        } else {
            $result['message'] = "Undefined action: {$log->getAction()}.";
        }

        return $result;
    }
}
