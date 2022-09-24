<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Emails;
use App\Repository\EmailsRepository;
use App\Repository\CouponRepository;
use App\Entity\Coupon;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('1xbet');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/change_password", name="reset_password")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $error = null;
        $success = null;

        if($request->isMethod(Request::METHOD_POST)){
            $result = $this->changePassword($request, $passwordEncoder);
            $success = $result[0];
            $error = $result[1];
        }

        return $this->render('security/reset_password.html.twig', compact("error", "success"));
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return array
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $password = $request->request->get('password');
        $newPassword = $request->request->get('newPassword');
        $error = null;
        $success = null;
        if( $passwordEncoder->isPasswordValid($this->getUser(), $password) ){
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(Emails::class)->findOneBy(['name'=>$this->getUser()->getUsername()]);
            $user->setPassword($passwordEncoder->encodePassword($this->getUser(), $newPassword));
            $em->persist($user);
            $em->flush();
            $success = "Mot de passe modifié avec succès";
        }else{
            $error = "Mot de passe incorrect";
        }

        return [$success, $error];
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $error = null;
        $success = null;
        if($request->getMethod() == Request::METHOD_POST){
            $result = $this->registerUser($request, $encoder);
            $success = $result[0];
            $error = $result[1];
        }

        return $this->render('security/register.html.twig', compact('error', 'success'));
    }

    private function registerUser(Request $request, UserPasswordEncoderInterface $encoder) {
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirmPassword');
        if(!$password || strlen($password) < 8);
        $error = null;
        $success = null;
        if(!$password || strlen($password) < 8)
            $error = "Le mot de passe doit comporter au moins 8 caractéres";
        if($password != $confirmPassword)
            $error = "Les mots de passe ne correspondent pas";
        if(!$name || strlen($name) < 3)
            $error = "Renseignez un nom correct";
        if(!preg_match("#.{2,}@[a-zA-Z]{2,}\.[a-zA-Z]{2,5}#", $email))
            $error = "Renseignez une adresse email correcte";


        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(Emails::class)->findByEmail($email);
        if($user)
            $error = "Cette adresse email est déjà associée à un compte";
        $user = new Emails();
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($encoder->encodePassword($user, $password));
        if($error == '') {
            $em->persist($user);
            $em->flush();
            $success = "Compte créé avec succès";
        }
            
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return [$success, $error];
    }

}
