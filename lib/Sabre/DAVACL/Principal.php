<?php

/**
 * Principal class
 *
 * This class represents a user in the directory tree.
 * Many WebDAV specs require a user to show up in the directory 
 * structure, so they can query for information.
 * 
 * @package Sabre
 * @subpackage DAVACL
 * @version $Id$
 * @copyright Copyright (C) 2007-2010 Rooftop Solutions. All rights reserved.
 * @author Evert Pot (http://www.rooftopsolutions.nl/) 
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
class Sabre_DAVACL_Principal extends Sabre_DAV_Node implements Sabre_DAV_IProperties {

    /**
     * Struct with principal information.
     *
     * Requirement elements are 'userId' and 'displayName'.
     * 
     * @var mixed
     */
    protected $principalInfo;

    /**
     * Creates the principal object 
     * 
     * @param array $principalInfo 
     */
    public function __construct(array $principalInfo) {

        if (!isset($principalInfo['userId'])) {
            throw new Sabre_DAV_Exception('The principalInfo array must have a userId element');
        }
        if (!isset($principalInfo['displayName'])) {
            throw new Sabre_DAV_Exception('The principalInfo array must have a displayName element');
        }
        $this->principalInfo = $principalInfo;

    }

    /**
     * Returns the name of the element 
     * 
     * @return void
     */
    public function getName() {

        return $this->principalInfo['userId'];

    }

    /**
     * Returns the name of the user 
     * 
     * @return void
     */
    public function getDisplayName() {

        return $this->principalInfo['displayName'];

    }

    /**
     * Returns a list of properties 
     * 
     * @param array $requestedProperties 
     * @return void
     */
    public function getProperties($requestedProperties) {

        if (!count($requestedProperties)) {
           
            // If all properties were requested
            // we will only returns properties from this list
            $requestedProperties = array(
                '{DAV:}resourcetype',
                '{DAV:}displayname',
            );

        }

        /* Always returning resourcetype (for now) */
        $newProperties['{DAV:}resourcetype'] = new Sabre_DAV_Property_ResourceType('{DAV:}principal');

        if (in_array('{DAV:}alternate-URI-set',$requestedProperties)) 
            $newProperties['{DAV:}alternate-URI-set'] = null;

        if (in_array('{DAV:}principal-URL',$requestedProperties))
            $newProperties['{DAV:}principal-URL'] = new Sabre_DAV_Property_Href('principals/' . $this->getName() . '/');

        if (in_array('{DAV:}group-member-set',$requestedProperties))
            $newProperties['{DAV:}group-member-set'] = null;

        if (in_array('{DAV:}group-membership',$requestedProperties))
            $newProperties['{DAV:}group-membership'] = null;
        
        if (in_array('{DAV:}displayname',$requestedProperties)) 
            $newProperties['{DAV:}displayname'] = $this->getDisplayName();

        return $newProperties;
        

    }

    /**
     * Updates this principals properties.
     *
     * Currently this is not supported
     * 
     * @param array $properties 
     * @return void
     */
    public function updateProperties($properties) {

        throw new Sabre_DAV_Exception_PermissionDenied('Updating properties is not supported');

    }


}