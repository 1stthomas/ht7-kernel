<?php

namespace Ht7\Kernel\Utility\Routines;

/**
 *
 * @author Thomas Pluess
 */
interface Routinable
{

    public function getArgs();

    /**
     * Get the name of the routine.
     *
     * @return  string          The name of the present routine.
     */
    public function getName();

    /**
     * Get the response of the routine.
     *
     * @return  Ht7\CmsSimple\Utility\Routines\Response
     *                          The response of the routine and its subroutines.
     */
    public function getResponse();

    /**
     * Process the present routine.
     */
    public function run();

    /**
     * Set the arguments of the present routine.
     *
     * @param   array   $args
     */
    public function setArgs(array $args);

    /**
     * Set the name of the present routine.
     *
     * @param   string  $name
     */
    public function setName(string $name);

    /**
     * Set the response of the present routine.
     *
     * @param   Ht7\CmsSimple\Utility\Routines\Response   $response
     */
    public function setResponse(array $response);
}
