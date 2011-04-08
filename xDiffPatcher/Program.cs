using System;
using System.Collections.Generic;
using System.Linq;
using System.Windows.Forms;
using System.Threading;

namespace xDiffPatcher
{
    static class Program
    {
        /// <summary>
        /// The main entry point for the application.
        /// </summary>
        [STAThread]
        static void Main()
        {
            Application.SetUnhandledExceptionMode(UnhandledExceptionMode.CatchException);
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            
            Application.ThreadException += new System.Threading.ThreadExceptionEventHandler(GlobalExceptionHandler);
            Application.Run(new frmMain());
        }

        static string GatherInfo()
        {
            var sb = new System.Text.StringBuilder();

            foreach (Form f in Application.OpenForms)
            {
                if (f is frmMain)
                {
                    frmMain frm = (frmMain)f;
                    if (frm.file != null)
                    {
                        sb.Append("Diff File: ");
                        sb.Append(frm.file.FileInfo.Name);
                        sb.Append(Environment.NewLine);
                        sb.Append(Environment.NewLine);
                        sb.Append("Patches:");

                        foreach (DiffPatchBase b in frm.file.xPatches.Values)
                        {
                            if (b is DiffPatch)
                            {
                                sb.Append(Environment.NewLine);
                                sb.Append("[");
                                if (((DiffPatch)b).Apply)
                                    sb.Append("x] ");
                                else
                                    sb.Append(" ] ");
                                sb.Append(((DiffPatch)b).Name);
                            }
                            else if (b is DiffPatchGroup)
                            {
                                foreach (DiffPatch p in ((DiffPatchGroup)b).Patches)
                                {
                                    sb.Append(Environment.NewLine);
                                    sb.Append("[");
                                    if (((DiffPatch)p).Apply)
                                        sb.Append("x] ");
                                    else
                                        sb.Append(" ] ");
                                    sb.Append(((DiffPatch)p).Name);
                                }

                            }
                        }
                    }
                }
            }

            return sb.ToString();
        }

        static string ExceptionToString(Exception e)
        {
            var sb = new System.Text.StringBuilder();

            if (e.InnerException != null)
            {
                sb.Append("(Inner Exception)");
                sb.Append(Environment.NewLine);
                sb.Append(Program.ExceptionToString(e.InnerException));
                sb.Append(Environment.NewLine);
                sb.Append("(Outer Exception)");
                sb.Append(Environment.NewLine);
            }

            sb.Append("Exception Source:      ");
            try
            {
                sb.Append(e.Source);
            }
            catch (Exception e2)
            {
                sb.Append(e2.Message);
            }
            sb.Append(Environment.NewLine);
            
            sb.Append("Exception Type:        ");
            try 
            {
                sb.Append(e.GetType().FullName);
            } 
            catch (Exception e2)
            {
                sb.Append(e2.Message);
            }
            sb.Append(Environment.NewLine);

            sb.Append("Exception Message:     ");
            try
            {
                sb.Append(e.Message);
            }
            catch (Exception e2)
            {
                sb.Append(e2.Message);
            }
            sb.Append(Environment.NewLine);

            sb.Append("Exception Target Site: ");
            try
            {
                sb.Append(e.TargetSite.Name);
            }
            catch (Exception e2)
            {
                sb.Append(e2.Message);
            }
            sb.Append(Environment.NewLine);
            sb.Append(Environment.NewLine);

            try
            {
                var x = e.StackTrace;
                sb.Append(x);
            }
            catch (Exception e2)
            {
                sb.Append(e2.Message);
            }
            sb.Append(Environment.NewLine);
            sb.Append(Environment.NewLine);

            sb.Append("Additional info:");
            sb.Append(Environment.NewLine);
            sb.Append(GatherInfo());

            return sb.ToString();
        }

        static void GlobalExceptionHandler(object sender, ThreadExceptionEventArgs e)
        {
            var diag = new frmErrorHandler();

            diag.txtInfo.Text = ExceptionToString(e.Exception);

            diag.ShowDialog();
        }
    }
}
