<?php

namespace App\Controller;

use App\Entity\SchoolYear;
use App\Form\SchoolYearType;
use App\Repository\SchoolYearRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/school-year')]
class SchoolYearController extends AbstractController
{
    #[Route('/', name: 'app_school_year_index', methods: ['GET'])]
    public function index(SchoolYearRepository $schoolYearRepository): Response
    {
        return $this->render('school_year/index.html.twig', [
            'school_years' => $schoolYearRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_school_year_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SchoolYearRepository $schoolYearRepository): Response
    {
        $schoolYear = new SchoolYear();
        $form = $this->createForm(SchoolYearType::class, $schoolYear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $schoolYearRepository->save($schoolYear, true);

            return $this->redirectToRoute('app_school_year_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('school_year/new.html.twig', [
            'school_year' => $schoolYear,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_school_year_show', methods: ['GET'])]
    public function show(SchoolYear $schoolYear): Response
    {
        return $this->render('school_year/show.html.twig', [
            'school_year' => $schoolYear,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_school_year_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SchoolYear $schoolYear, SchoolYearRepository $schoolYearRepository): Response
    {
        $form = $this->createForm(SchoolYearType::class, $schoolYear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $schoolYearRepository->save($schoolYear, true);

            return $this->redirectToRoute('app_school_year_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('school_year/edit.html.twig', [
            'school_year' => $schoolYear,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_school_year_delete', methods: ['POST'])]
    public function delete(Request $request, SchoolYear $schoolYear, SchoolYearRepository $schoolYearRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$schoolYear->getId(), $request->request->get('_token'))) {
            $schoolYearRepository->remove($schoolYear, true);
        }

        return $this->redirectToRoute('app_school_year_index', [], Response::HTTP_SEE_OTHER);
    }
}
