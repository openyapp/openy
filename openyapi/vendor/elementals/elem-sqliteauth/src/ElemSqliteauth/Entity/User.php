<?php
namespace ElemSqliteauth\Entity;

use Zend\Form\Annotation;

/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("User")
 * @Annotation\Attributes({"class":"form-signin"})
 */
class User
{
    /**
     * @Annotation\Type("Zend\Form\Element\Csrf")
     * @Annotation\Required({"required":"true" })
     * //@Annotation\Options({"timeout":"300"})
     */
    public $security;
    
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * //@Annotation\Options({"label":"Username:"})
     * @Annotation\Attributes({"class":"form-control"})
     * @Annotation\Attributes({"placeholder":"Username"})
     */
    public $username;
     
    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * //@Annotation\Options({"label":"Password:"})
     * @Annotation\Attributes({"class":"form-control"})
     * @Annotation\Attributes({"placeholder":"Password"})
     */
    public $password;
     
    /**
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Options({"label":"Remember Me ?:"})
     */
//     public $rememberme;
     
    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Submit"})
     * @Annotation\Attributes({"class":"btn btn-lg btn-primary btn-block"})
     */
    public $submit;
}