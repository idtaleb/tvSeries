<?php
/**
 * Created by PhpStorm.
 * User: you
 * Date: 12/02/2017
 * Time: 19:15
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class SecurityController
 * @package AppBundle\Controller\login
 */
class SecurityController extends Controller
{

    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            $this->addFlash('login','Error Login');
        }

        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('re_user');
        }

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    public function userAction()
    {
        if (FALSE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('re_login');
        }

//	if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
//	return $this->redirectToRoute('re_index');
//	return new Response('', 500);
//	}
        else {
            return $this->redirectToRoute('re_index');
        }
    }

    public function logoutAction()
    {
        if ($this->get('security.token_storage')->getToken()->getUser()) {
            $this->get('security.token_storage')->setToken(null);
            $this->addFlash('logout','Logout');
            return $this->redirectToRoute('re_login');
        }
    }
}