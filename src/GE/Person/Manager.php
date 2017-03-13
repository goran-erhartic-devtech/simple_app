<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 23/2/2017
 * Time: 12:57 PM
 */
namespace GE\Person;

class Manager extends AbstractHuman implements ManagerInterface
{
    private $projects;

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->projects;
    }

    /**
     * @param mixed $projects
     * @return $this
     */
    public function setProject($projects)
    {
        $this->projects = $projects;
        return $this;
    }

    public function iterateProperties()
    {
        foreach ($this as $key => $value) {
            if (is_array($value)) {
                $a = implode(',', $value);
                echo "$key => $a<br>";

            } else {
                echo "$key => $value<br>";
            }
        }
        echo "<br>";
    }
}