<?php
namespace Api\App\Planning;

use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Message\ResponseInterface as IResponse;

/**
 * Contrôleur de plannings
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 0.1
 * @see \Api\Tests\Units\App\Planning\Controller
 *
 * Ne devrait être contacté que par le routeur
 * Ne devrait contacter que le Planning\Repository
 */
final class Controller extends \Api\App\Libraries\Controller
{
    public function post(IRequest $request, IResponse $response, array $arguments)
    {
        ddd($request, $response, $arguments);
    }

    /*************************************************
     * GET
     *************************************************/

    /**
     * Execute l'ordre HTTP GET
     *
     * @return IResponse
     */
    public function get(IRequest $request, IResponse $response, array $arguments)
    {
        if (!isset($arguments['planningId'])) {
            return $this->getList();
        }

        return $this->getOne($arguments['planningId']);
    }

    /**
     * Retourne un élément unique
     *
     * @param int $id ID de l'élément
     *
     * @return IResponse, 404 si l'élément n'est pas trouvé, 200 sinon
     * @throws \Exception en cas d'erreur inconnue (fallback, ne doit pas arriver)
     */
    private function getOne($id)
    {
        ddd('getOne');
        $id = (int) $id;
        $code = -1;
        $data = [];
        try {
            $planning = $this->repository->getOne($id);
            $code = 200;
            $data = [
                'code' => $code,
                'status' => 'success',
                'message' => '',
                'data' => $this->buildData($planning),
            ];
        } catch (\DomainException $e) {
            $code = 404;
            $data = [
                'code' => $code,
                'status' => 'error',
                'message' => 'Not Found',
                'data' => 'Element « plannings#' . $id . ' » is not a valid resource',
            ];
        } catch (\Exception $e) {
            throw $e;
        } finally {
            return $this->response->withJson($data, $code);
        }
    }

    /**
     * Retourne un tableau de plannings
     *
     * @return ResponseInterface
     * @throws \Exception en cas d'erreur inconnue (fallback, ne doit pas arriver)
     */
    private function getList()
    {
        ddd('getListe');
        $code = -1;
        $data = [];
        try {
            $plannings = $this->repository->getList(
                $this->request->getQueryParams()
            );
            $models = [];
            foreach ($plannings as $planning) {
                $models[] = $this->buildData($planning);
            }
            $code = 200;
            $data = [
                'code' => $code,
                'status' => 'success',
                'message' => '',
                'data' => $models,
            ];
        } catch (\UnexpectedValueException $e) {
            $code = 404;
            $data = [
                'code' => $code,
                'status' => 'error',
                'message' => 'Not Found',
                'data' => 'No result',
            ];
        } catch (\Exception $e) {
            throw $e;
        } finally {
            return $this->response->withJson($data, $code);
        }
    }

    /**
     * Construit le « data » du json
     *
     * @param Model $model Planning
     *
     * @return array
     */
    private function buildData(Model $model)
    {
        return [
            'id' => $model->getId(),
            'name' => $model->getName(),
            'status' => $model->getStatus(),
        ];
    }
}
