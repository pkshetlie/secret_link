<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, EntityManagerInterface $em, ContentRepository $contentRepository): Response
    {
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            do {
                $link = $this->generateRandomString();
                $exist = $contentRepository->findBy(['link' => $link]);
            } while ($exist != null);
            $content->setLink($link);
            $em->persist($content);
            $em->flush();
            return $this->redirectToRoute('confirmation_link', ['link' => $link]);
        }
        return $this->renderForm('home/index.html.twig', [
            'form' => $form
        ]);
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    /**
     * @Route("/locale/{locale}", name="change_locale")
     */
    public function changeLocale(Request $request, string $locale)
    {
        $request->getSession()->set('_locale', $locale);
        $request->getSession()->set('_locale', $locale);
        $request->setLocale($locale);
        if ($request->headers->get('referer') == null) {
            return $this->redirectToRoute('app_home');
        }
        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * @Route("/{link}", name="link_open_confirmation")
     */
    public function DisplayConfirmation(Content $content = null): Response
    {
        if ($content == null) {
            $this->addFlash("warning", "This link is not available anymore!");
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/ask_for_confirmation.html.twig', [
            "content" => $content
        ]);
    }

    /**
     * @Route("/{link}/confirmed", name="link_open_confirmed")
     */
    public function DisplayContent( EntityManagerInterface $em,TranslatorInterface $translator,Content $content = null): Response
    {
        if ($content == null) {
            $this->addFlash("warning", $translator->trans("This link is not available anymore!"));
            return $this->redirectToRoute('app_home');
        }
        $em->remove($content);
        $em->flush();
        return $this->render('home/display_content.html.twig', [
            "content" => $content
        ]);
    }

    /**
     * @Route("/confirm/{link}", name="confirmation_link")
     */
    public function ConfirmationLink(TranslatorInterface $translator, Content $content = null): Response
    {
        if ($content == null) {
            $this->addFlash("warning", $translator->trans("This link is not available anymore!"));
            return $this->redirectToRoute('app_home');
        }
        return $this->render('home/confirmation.html.twig', [
            "content" => $content
        ]);
    }

}
