using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using OpenSim.Framework.Servers.HttpServer;

namespace OpenSim.Grid.MoneyServer
{
    /// <summary>
    /// StreamHandler für JSON-APIs im MoneyServer (z.B. für /api/json)
    /// </summary>
    public class JsonStreamHandler : CustomSimpleStreamHandler
    {
        /// <summary>
        /// Erstellt einen neuen JSON-StreamHandler.
        /// </summary>
        /// <param name="path">Der Pfad (z.B. "/api/json")</param>
        /// <param name="processAction">Die Handler-Methode (Action mit IOSHttpRequest, IOSHttpResponse)</param>
        public JsonStreamHandler(string path, Action<IOSHttpRequest, IOSHttpResponse> processAction)
            : base(path, processAction)
        {
        }
    }
}